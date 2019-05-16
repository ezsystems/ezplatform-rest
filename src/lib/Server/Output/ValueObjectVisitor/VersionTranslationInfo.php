<?php

/**
 * This file is part of the eZ Publish Kernel package.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace EzSystems\EzPlatformRest\Server\Output\ValueObjectVisitor;

use EzSystems\EzPlatformRest\Output\ValueObjectVisitor;
use EzSystems\EzPlatformRest\Output\Generator;
use EzSystems\EzPlatformRest\Output\Visitor;
use EzSystems\EzPlatformRest\Server\Values\VersionTranslationInfo as VersionTranslationInfoValue;

/**
 * Version value object visitor.
 */
class VersionTranslationInfo extends ValueObjectVisitor
{
    /**
     * Visit struct returned by controllers.
     *
     * @param \EzSystems\EzPlatformRest\Output\Visitor $visitor
     * @param \EzSystems\EzPlatformRest\Output\Generator $generator
     * @param \EzSystems\EzPlatformRest\Server\Values\VersionTranslationInfo $data
     */
    public function visit(Visitor $visitor, Generator $generator, $data)
    {
        $versionInfo = $data->getVersionInfo();
        if (empty($versionInfo->languageCodes)) {
            return;
        }

        $generator->startObjectElement('VersionTranslationInfo');
        $this->visitVersionTranslationInfoAttributes($visitor, $generator, $data);
        $generator->endObjectElement('VersionTranslationInfo');
    }

    protected function visitVersionTranslationInfoAttributes(Visitor $visitor, Generator $generator, VersionTranslationInfoValue $versionTranslationInfo)
    {
        $versionInfo = $versionTranslationInfo->getVersionInfo();

        // single language-independent conditions for deleting Translation
        $canDelete = count($versionInfo->languageCodes) >= 2 && $versionInfo->isDraft();

        $generator->startList('Language');
        foreach ($versionInfo->languageCodes as $languageCode) {
            $generator->startHashElement('Language');

            $generator->startValueElement('languageCode', $languageCode);
            $generator->endValueElement('languageCode');

            // check conditions for deleting Translation
            if ($canDelete && $languageCode !== $versionInfo->contentInfo->mainLanguageCode) {
                $generator->startHashElement('DeleteTranslation');
                $path = $this->router->generate(
                    'ezpublish_rest_deleteTranslationFromDraft',
                    [
                        'contentId' => $versionInfo->contentInfo->id,
                        'versionNumber' => $versionInfo->versionNo,
                        'languageCode' => $languageCode,
                    ]
                );
                $generator->startAttribute('href', $path);
                $generator->endAttribute('href');
                $generator->endHashElement('DeleteTranslation');
            }

            $generator->endHashElement('Language');
        }
        $generator->endList('Language');
    }
}
