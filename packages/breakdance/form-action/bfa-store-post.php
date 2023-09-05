<?php

namespace VXN\Express\Breakdance\Form_Action;

use function Breakdance\Elements\control;
use function Breakdance\Elements\controlSection;
/** 
 * This Trait used by BFA_Store_Post_Factory
 * @package VXN\Express\Breakdance\Form_Action
 * @author Vaksin <dev@vaks.in>
 * @since 1.1
 */
trait BFA_Store_Post {

    /** @return mixed  */
    abstract public static function post_type();

    /**
     * Get the displayable label of the action.
     * @return string
     */
    public static function name()
    {
        $post_type = static::post_type();
        return sprintf(__('Store as %s', VXN_EXPRESS_ADDON_DOMAIN), $post_type['name']) ;
    }

    /**
     * Get the URL friendly slug of the action.
     * @return string
     */
    public static function slug()
    {
        $post_type = static::post_type();
        return sprintf('vxn_store_%s', $post_type['post_type']) ;
    }

    /**
     * Get controls for the builder
     * @return array
     */
    public function controls()
    {
        $controls = [
            control('title', 'Title', 
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
            control('content', 'Content', 
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

        $control_sections = [];
        $sections = static::post_type()['sections'] ; //call_user_func(array(static::post_type(), 'sections'));
        foreach($sections as $section){
            $children = [];
            foreach($section['fields'] as $field){
                $children[] =  control($field['id'], $field['label'], [
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
                ]);
            }

            $control_sections[] = controlSection($section['id'], $section['title'], $children);
        }
        return array_merge($controls, $control_sections) ;
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
        $control = $settings['actions'][sprintf('vxn_store_%s', call_user_func(array(static::post_type(), 'post_type')))];
        
        $id = wp_insert_post([
            'post_type'   => call_user_func(array(static::post_type(), 'post_type')),
            'post_title'  => parent::renderData($form, $control['title'] ?? sprintf(__('%s Submision', VXN_EXPRESS_ADDON_DOMAIN),  call_user_func(array(static::post_type(), 'name')))),
            'post_content'  => parent::renderData($form, $control['content'] ?? ''),
            'post_status' => 'draft'
        ]);

        if (is_wp_error($id)) {
            return [
                'type' => 'error',
                'message' => $id
            ];
        }

        $sections = static::post_type()['sections'] ; 
        foreach($sections as $section){
            foreach($section['fields'] as $field){
                $field['value'] = parent::renderData($form, $control[$field['id']] ?? '');

                add_post_meta((int) $id, $field['id'], $field->get_sanitized_value(), false);
            }
        }

        return [
            'type' => 'success',
            'id' => $id
        ];
    }
}
