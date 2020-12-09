<?php
namespace NeosRulez\CloudTranslation\DataSource;

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\PersistenceManagerInterface;
use Neos\Utility\TypeHandling;
use Neos\Neos\Service\DataSource\AbstractDataSource;
use Neos\ContentRepository\Domain\Model\NodeInterface;
use Neos\Eel\FlowQuery\FlowQuery;
use Neos\Eel\FlowQuery\Operations;
use Google\Cloud\Translate\TranslateClient;

class LanguageDataSource extends AbstractDataSource {

    /**
     * @Flow\Inject
     * @var \Neos\ContentRepository\Domain\Service\ContextFactoryInterface
     */
    protected $contextFactory;

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
     * @var string
     */
    protected static $identifier = 'neosrulez-cloudtranslate-languages';

    /**
     * @inheritDoc
     */
    public function getData(NodeInterface $node = null, array $arguments = array()) {
        $options = [];
        $translate = new TranslateClient();
        $languages = $translate->localizedLanguages([
            'key' => $this->settings['apiKey'],
        ]);
        foreach ($languages as $language) {
            $options[] = [
                'label' => $language['name'],
                'value' => $language['code']
            ];
        }
        return $options;
    }

}
