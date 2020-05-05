<?php

    namespace fatfish\zoom\fieldtypes;
    use craft\base\ElementInterface;
    use craft\base\Field;
    use craft\base\PreviewableFieldInterface;
    use Craft;
    use fatfish\zoom\Zoom;

    class zoomfield extends Field implements PreviewableFieldInterface
    {

        public static function displayName(): string {

            return Craft::t('app','Zoom');
        }

        public function normalizeValue($value, ElementInterface $element = null) {

            if(is_array($value))
            {
                return $value;
            }
            return json_decode($value);
        }

        public  function getInputHtml($value, ElementInterface $element = null): string {

            $options=[];
            $Meetings= Zoom::$plugin->zoomeeting->list_meetings();
            foreach ($Meetings->meetings as $meeting)
            {

                $options[$meeting->id]=$meeting->topic;


            }

            $instanceId = str_replace('.', '', uniqid('', true));
            return Craft::$app->getView()->renderTemplate('zoom/meetings/_select', [
                    'wrapper_class' => 'select',
                    'instance_wrapper_class' => 'select-' . $instanceId,
                    'name' => $this->handle,
                    'value' => $value,
                    'field' => $this,
                    'options' => $options,
                    'type' => 'zoommeeting',
            ]);


        }

    }
