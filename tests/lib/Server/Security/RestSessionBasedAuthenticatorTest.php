<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace Ibexa\Tests\Rest\Server\Security;

use Ibexa\Contracts\Core\Repository\Values\User\User;
use Ibexa\Core\MVC\ConfigResolverInterface;
use Ibexa\Core\MVC\Symfony\Security\User as EzUser;
use Ibexa\Rest\Server\Exceptions\InvalidUserTypeException;
use Ibexa\Rest\Server\Exceptions\UserConflictException;
use Ibexa\Rest\Server\Security\RestAuthenticator;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\Logout\LogoutHandlerInterface;
use Symfony\Component\Security\Http\Logout\SessionLogoutHandler;
use Symfony\Component\Security\Http\SecurityEvents;

class RestSessionBasedAuthenticatorTest extends TestCase
{
    public const PROVIDER_KEY = 'test_key';

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $tokenStorage;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $authenticationManager;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $eventDispatcher;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $configResolver;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $logger;

    /**
     * @var \Ibexa\Rest\Server\Security\RestAuthenticator
     */
    private $authenticator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->authenticationManager = $this->createMock(AuthenticationManagerInterface::class);
        $this->eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $this->configResolver = $this->createMock(ConfigResolverInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->authenticator = new RestAuthenticator(
            $this->tokenStorage,
            $this->authenticationManager,
            self::PROVIDER_KEY,
            $this->eventDispatcher,
            $this->configResolver,
            $this->logger
        );
    }

    public function testAuthenticateAlreadyHaveSessionToken()
    {
        $username = 'foo_user';
        $password = 'publish';

        $existingToken = $this->getTokenInterfaceMock();
        $this->tokenStorage
            ->expects($this->once())
            ->method('getToken')
            ->willReturn($existingToken);

        $existingToken
            ->expects($this->once())
            ->method('getUsername')
            ->willReturn($username);
        $existingToken
            ->expects($this->once())
            ->method('setAttribute')
            ->with('isFromSession', true);

        $request = new Request();
        $request->attributes->set('username', $username);
        $request->attributes->set('password', $password);

        $this->assertSame($existingToken, $this->authenticator->authenticate($request));
    }

    public function testAuthenticateNoTokenFound()
    {
        $this->expectException(TokenNotFoundException::class);
        $username = 'foo_user';
        $password = 'publish';

        $existingToken = $this->getTokenInterfaceMock();
        $this->tokenStorage
            ->expects($this->once())
            ->method('getToken')
            ->willReturn($existingToken);

        $existingToken
            ->expects($this->once())
            ->method('getUsername')
            ->willReturn(__METHOD__);

        $request = new Request();
        $request->attributes->set('username', $username);
        $request->attributes->set('password', $password);

        $usernamePasswordToken = new UsernamePasswordToken($username, $password, self::PROVIDER_KEY);
        $this->authenticationManager
            ->expects($this->once())
            ->method('authenticate')
            ->with($this->equalTo($usernamePasswordToken))
            ->willReturn(null);

        $this->logger
            ->expects($this->once())
            ->method('error');

        $this->authenticator->authenticate($request);
    }

    public function testAuthenticateInvalidUser()
    {
        $this->expectException(InvalidUserTypeException::class);
        $username = 'foo_user';
        $password = 'publish';

        $existingToken = $this->getTokenInterfaceMock();
        $existingToken
            ->expects($this->once())
            ->method('getUsername')
            ->willReturn(__METHOD__);

        $request = new Request();
        $request->attributes->set('username', $username);
        $request->attributes->set('password', $password);

        $usernamePasswordToken = new UsernamePasswordToken($username, $password, self::PROVIDER_KEY);
        $authenticatedToken = $this->getUsernamePasswordTokenMock();
        $this->authenticationManager
            ->expects($this->once())
            ->method('authenticate')
            ->with($this->equalTo($usernamePasswordToken))
            ->willReturn($authenticatedToken);

        $this->tokenStorage
            ->expects($this->once())
            ->method('setToken')
            ->with($authenticatedToken);

        $this->eventDispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with(
                $this->equalTo(new InteractiveLoginEvent($request, $authenticatedToken)),
                SecurityEvents::INTERACTIVE_LOGIN
            );

        $this->tokenStorage
            ->expects($this->exactly(2))
            ->method('getToken')
            ->will(
                $this->onConsecutiveCalls($existingToken, $authenticatedToken)
            );

        $authenticatedToken
            ->expects($this->once())
            ->method('getUser')
            ->willReturn('not_an_ez_user');

        $this->logger
            ->expects($this->once())
            ->method('error');

        $this->authenticator->authenticate($request);
    }

    /**
     * @param $userId
     *
     * @return \Ibexa\Core\MVC\Symfony\Security\User
     */
    private function createUser($userId)
    {
        $apiUser = $this->createMock(User::class);
        $apiUser
            ->expects($this->any())
            ->method('getUserId')
            ->willReturn($userId);

        return new EzUser($apiUser);
    }

    public function testAuthenticateUserConflict()
    {
        $this->expectException(UserConflictException::class);
        $username = 'foo_user';
        $password = 'publish';

        $existingUser = $this->createUser(123);
        $existingToken = $this->getUsernamePasswordTokenMock();
        $existingToken
            ->expects($this->once())
            ->method('getUsername')
            ->willReturn(__METHOD__);
        $existingToken
            ->expects($this->once())
            ->method('getUser')
            ->willReturn($existingUser);

        $request = new Request();
        $request->attributes->set('username', $username);
        $request->attributes->set('password', $password);

        $usernamePasswordToken = new UsernamePasswordToken($username, $password, self::PROVIDER_KEY);
        $authenticatedToken = $this->getUsernamePasswordTokenMock();
        $this->authenticationManager
            ->expects($this->once())
            ->method('authenticate')
            ->with($this->equalTo($usernamePasswordToken))
            ->willReturn($authenticatedToken);

        $this->eventDispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with(
                $this->equalTo(new InteractiveLoginEvent($request, $authenticatedToken)),
                SecurityEvents::INTERACTIVE_LOGIN
            );

        $this->tokenStorage
            ->expects($this->at(0))
            ->method('getToken')
            ->willReturn($existingToken);
        $this->tokenStorage
            ->expects($this->at(1))
            ->method('setToken')
            ->with($authenticatedToken);
        $this->tokenStorage
            ->expects($this->at(2))
            ->method('getToken')
            ->willReturn($authenticatedToken);
        $this->tokenStorage
            ->expects($this->at(3))
            ->method('setToken')
            ->with($existingToken);

        $authenticatedUser = $this->createUser(456);
        $authenticatedToken
            ->expects($this->once())
            ->method('getUser')
            ->willReturn($authenticatedUser);

        $this->configResolver
            ->expects($this->once())
            ->method('getParameter')
            ->with('anonymous_user_id')
            ->willReturn(10);

        $this->authenticator->authenticate($request);
    }

    public function testAuthenticatePreviouslyAnonymous()
    {
        $username = 'foo_user';
        $password = 'publish';

        $anonymousUserId = 10;
        $existingUser = $this->createUser($anonymousUserId);
        $existingToken = $this->getUsernamePasswordTokenMock();
        $existingToken
            ->expects($this->once())
            ->method('getUsername')
            ->willReturn(__METHOD__);
        $existingToken
            ->expects($this->once())
            ->method('getUser')
            ->willReturn($existingUser);

        $request = new Request();
        $request->attributes->set('username', $username);
        $request->attributes->set('password', $password);

        $usernamePasswordToken = new UsernamePasswordToken($username, $password, self::PROVIDER_KEY);
        $authenticatedToken = $this->getUsernamePasswordTokenMock();
        $this->authenticationManager
            ->expects($this->once())
            ->method('authenticate')
            ->with($this->equalTo($usernamePasswordToken))
            ->willReturn($authenticatedToken);

        $this->eventDispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with(
                $this->equalTo(new InteractiveLoginEvent($request, $authenticatedToken)),
                SecurityEvents::INTERACTIVE_LOGIN
            );

        $this->tokenStorage
            ->expects($this->at(0))
            ->method('getToken')
            ->willReturn($existingToken);
        $this->tokenStorage
            ->expects($this->at(1))
            ->method('setToken')
            ->with($authenticatedToken);
        $this->tokenStorage
            ->expects($this->at(2))
            ->method('getToken')
            ->willReturn($authenticatedToken);

        $authenticatedUser = $this->createUser(456);
        $authenticatedToken
            ->expects($this->once())
            ->method('getUser')
            ->willReturn($authenticatedUser);

        $this->configResolver
            ->expects($this->once())
            ->method('getParameter')
            ->with('anonymous_user_id')
            ->willReturn($anonymousUserId);

        $this->assertSame($authenticatedToken, $this->authenticator->authenticate($request));
    }

    public function testAuthenticate()
    {
        $username = 'foo_user';
        $password = 'publish';

        $existingToken = $this->getTokenInterfaceMock();
        $existingToken
            ->expects($this->once())
            ->method('getUsername')
            ->willReturn(__METHOD__);

        $request = new Request();
        $request->attributes->set('username', $username);
        $request->attributes->set('password', $password);

        $usernamePasswordToken = new UsernamePasswordToken($username, $password, self::PROVIDER_KEY);
        $authenticatedToken = $this->getUsernamePasswordTokenMock();
        $this->authenticationManager
            ->expects($this->once())
            ->method('authenticate')
            ->with($this->equalTo($usernamePasswordToken))
            ->willReturn($authenticatedToken);

        $this->eventDispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with(
                $this->equalTo(new InteractiveLoginEvent($request, $authenticatedToken)),
                SecurityEvents::INTERACTIVE_LOGIN
            );

        $this->tokenStorage
            ->expects($this->at(0))
            ->method('getToken')
            ->willReturn($existingToken);
        $this->tokenStorage
            ->expects($this->at(1))
            ->method('setToken')
            ->with($authenticatedToken);
        $this->tokenStorage
            ->expects($this->at(2))
            ->method('getToken')
            ->willReturn($authenticatedToken);

        $authenticatedUser = $this->createUser(456);
        $authenticatedToken
            ->expects($this->once())
            ->method('getUser')
            ->willReturn($authenticatedUser);

        $this->assertSame($authenticatedToken, $this->authenticator->authenticate($request));
    }

    public function testAuthenticatePreviousUserNonEz()
    {
        $username = 'foo_user';
        $password = 'publish';

        $existingUser = $this->createMock(UserInterface::class);
        $existingToken = $this->getUsernamePasswordTokenMock();
        $existingToken
            ->expects($this->once())
            ->method('getUsername')
            ->willReturn(__METHOD__);
        $existingToken
            ->expects($this->once())
            ->method('getUser')
            ->willReturn($existingUser);

        $request = new Request();
        $request->attributes->set('username', $username);
        $request->attributes->set('password', $password);

        $usernamePasswordToken = new UsernamePasswordToken($username, $password, self::PROVIDER_KEY);
        $authenticatedToken = $this->getUsernamePasswordTokenMock();
        $this->authenticationManager
            ->expects($this->once())
            ->method('authenticate')
            ->with($this->equalTo($usernamePasswordToken))
            ->willReturn($authenticatedToken);

        $this->eventDispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with(
                $this->equalTo(new InteractiveLoginEvent($request, $authenticatedToken)),
                SecurityEvents::INTERACTIVE_LOGIN
            );

        $this->tokenStorage
            ->expects($this->at(0))
            ->method('getToken')
            ->willReturn($existingToken);
        $this->tokenStorage
            ->expects($this->at(1))
            ->method('setToken')
            ->with($authenticatedToken);
        $this->tokenStorage
            ->expects($this->at(2))
            ->method('getToken')
            ->willReturn($authenticatedToken);

        $authenticatedUser = $this->createUser(456);
        $authenticatedToken
            ->expects($this->once())
            ->method('getUser')
            ->willReturn($authenticatedUser);

        $this->assertSame($authenticatedToken, $this->authenticator->authenticate($request));
    }

    public function testAuthenticatePreviousTokenNotUsernamePassword()
    {
        $username = 'foo_user';
        $password = 'publish';

        $existingToken = $this->getTokenInterfaceMock();
        $existingToken
            ->expects($this->once())
            ->method('getUsername')
            ->willReturn(__METHOD__);

        $request = new Request();
        $request->attributes->set('username', $username);
        $request->attributes->set('password', $password);

        $usernamePasswordToken = new UsernamePasswordToken($username, $password, self::PROVIDER_KEY);
        $authenticatedToken = $this->getUsernamePasswordTokenMock();
        $this->authenticationManager
            ->expects($this->once())
            ->method('authenticate')
            ->with($this->equalTo($usernamePasswordToken))
            ->willReturn($authenticatedToken);

        $this->eventDispatcher
            ->expects($this->once())
            ->method('dispatch')
            ->with(
                $this->equalTo(new InteractiveLoginEvent($request, $authenticatedToken)),
                SecurityEvents::INTERACTIVE_LOGIN
            );

        $this->tokenStorage
            ->expects($this->at(0))
            ->method('getToken')
            ->willReturn($existingToken);
        $this->tokenStorage
            ->expects($this->at(1))
            ->method('setToken')
            ->with($authenticatedToken);
        $this->tokenStorage
            ->expects($this->at(2))
            ->method('getToken')
            ->willReturn($authenticatedToken);

        $authenticatedUser = $this->createUser(456);
        $authenticatedToken
            ->expects($this->once())
            ->method('getUser')
            ->willReturn($authenticatedUser);

        $this->assertSame($authenticatedToken, $this->authenticator->authenticate($request));
    }

    public function testLogout()
    {
        $sessionLogoutHandler = $this->createMock(SessionLogoutHandler::class);
        $sessionLogoutHandler
            ->expects($this->never())
            ->method('logout');

        $token = $this->getTokenInterfaceMock();
        $this->tokenStorage
            ->expects($this->once())
            ->method('getToken')
            ->willReturn($token);

        $request = new Request();
        $request->setSession(new Session(new MockArraySessionStorage()));

        $logoutHandler1 = $this->createMock(LogoutHandlerInterface::class);
        $logoutHandler1
            ->expects($this->once())
            ->method('logout')
            ->with(
                $request,
                $this->isInstanceOf(Response::class),
                $token
            );
        $logoutHandler2 = $this->createMock(LogoutHandlerInterface::class);
        $logoutHandler2
            ->expects($this->once())
            ->method('logout')
            ->with(
                $request,
                $this->isInstanceOf(Response::class),
                $token
            );

        $this->authenticator->addLogoutHandler($sessionLogoutHandler);
        $this->authenticator->addLogoutHandler($logoutHandler1);
        $this->authenticator->addLogoutHandler($logoutHandler2);

        $this->assertInstanceOf(
            Response::class,
            $this->authenticator->logout($request)
        );
    }

    protected function getTokenInterfaceMock()
    {
        return $this->createMock(TokenInterface::class);
    }

    protected function getUsernamePasswordTokenMock()
    {
        return $this->createMock(UsernamePasswordToken::class);
    }
}

class_alias(RestSessionBasedAuthenticatorTest::class, 'EzSystems\EzPlatformRest\Tests\Server\Security\RestSessionBasedAuthenticatorTest');
