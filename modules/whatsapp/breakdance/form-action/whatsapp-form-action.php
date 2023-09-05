<?php
namespace VXN\Express\Whatsapp\Breakdance\Form_Action;
use Breakdance\Forms\Actions\Action;
use function Breakdance\Elements\control;

/** 
 * Breakdance Form Action
 * @package VXN\Express\Whatsapp\Breakdance\Form_Action;
 * @author Vaksin <dev@vaks.in>
 * @since 1.1
 */
Class Whatsapp_Form_Action extends Action {

    /**
     * Get the displayable label of the action.
     * @return string
     */
    public static function name()
    {
        return __('Send WhatsApp', VXN_EXPRESS_ADDON_DOMAIN) ;
    }

    /**
     * Get the URL friendly slug of the action.
     * @return string
     */
    public static function slug()
    {
        return 'vxn_send_whatsapp' ;
    }

    /**
     * Get controls for the builder
     * @return array
     */
    public function controls()
    {
        $controls = [
            control('name', 'Name Field', 
                [
                    'type' => 'text',
                    'layout' => 'vertical',
                    'variableOptions' => [
                        'enabled' => true,
                        'populate' => [
                            'path' => 'content.form.fields',
                            'text' => 'label',
                            'value' => 'advanced.id'
                        ]
                    ]
                ]
            ),
            control('email', 'Email Field', 
                [
                    'type' => 'text',
                    'layout' => 'vertical',
                    'variableOptions' => [
                        'enabled' => true,
                        'populate' => [
                            'path' => 'content.form.fields',
                            'text' => 'label',
                            'value' => 'advanced.id'
                        ]
                    ]
                ]
            ),             
            control('phone', 'Phone Field', 
                [
                    'type' => 'text',
                    'layout' => 'vertical',
                    'variableOptions' => [
                        'enabled' => true,
                        'populate' => [
                            'path' => 'content.form.fields',
                            'text' => 'label',
                            'value' => 'advanced.id'
                        ]
                    ]
                ]
            ),
            control('text', 'Message Field', 
                [
                    'type' => 'text',
                    'layout' => 'vertical',
                    'variableOptions' => [
                        'enabled' => true,
                        'populate' => [
                            'path' => 'content.form.fields',
                            'text' => 'label',
                            'value' => 'advanced.id'
                        ]
                    ]
                ]
            ),           
        ];


        return $controls ;
    }

    /**
     * Does something on form submission
     * @param FormData $form
     * @param FormSettings $settings
     * @param FormExtra $extra
     * @return ActionSuccess|ActionError|array<array-key, ActionSuccess|ActionError>
     */
    public function run($form, $settings, $extra)
    {
        return ['type' => 'success'];
    }
}
