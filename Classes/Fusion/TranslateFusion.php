<?php
namespace NeosRulez\CloudTranslation\Fusion;

use Neos\Flow\Annotations as Flow;
use Neos\Fusion\FusionObjects\AbstractFusionObject;

use Google\Cloud\Translate\V2\TranslateClient;

class TranslateFusion extends AbstractFusionObject {

    /**
     * @var array
     */
    protected $settings;

    /**
     * @param array $settings
     * @return void
     */
    public function injectSettings(array $settings) {
        $this->settings = $settings;
    }

    /**
     * @return array
     */
    public function evaluate() {
        $content = $this->fusionValue('content');
        $targetLanguage = $this->fusionValue('targetLanguage');
        $response = false;
        if($content && $targetLanguage) {
            $translate = new TranslateClient();
            $result = $translate->translate($content, [
                'target' => $targetLanguage,
                'key' => $this->settings['CloudTranslationApi']['apiKey'],
                'model' => $this->settings['CloudTranslationApi']['model'],
                'format' => $this->settings['CloudTranslationApi']['format'],
            ]);
            $response = str_replace('& nbsp;', '', str_replace('nbsp &;', '', $result['text']));
        }
        return $response;
    }
}