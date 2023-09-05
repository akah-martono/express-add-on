<?php
namespace VXN\Express\Whatsapp\Breakdance\Dynamic_Fields;

use Breakdance\DynamicData\StringData;
use Breakdance\DynamicData\StringField;
use VXN\Express\Helper\Util;

class Whatsapp_Url extends StringField
{
    /**
     * @inheritDoc
     */
    public function label()
    {
        return __('WhatsApp URL', VXN_EXPRESS_ADDON_DOMAIN);
    }

    /**
     * @inheritDoc
     */
    public function category()
    {
        return __('Express WhatsApp', VXN_EXPRESS_ADDON_DOMAIN);
    }

    /**
     * @inheritDoc
     */
    public function slug()
    {
        return 'vxn-whatsapp-url';
    }

    /**
     * @inheritDoc
     */    
    public function returnTypes()
    {
        return ['url'];
    }

    /**
     * @inheritDoc
     */        
    public function controls()
    {
        return [
            \Breakdance\Elements\control('whatsapp_text', 'Template', [
                'type' => 'dropdown',
                'layout' => 'vertical',
                'items' => [
                    ['text' => __('Default Text', VXN_EXPRESS_ADDON_DOMAIN), 'value' => '[vxn_wa_text]'], 
                    ['text' => __('Order Text', VXN_EXPRESS_ADDON_DOMAIN), 'value' => 'Order'],
                    ['text' => __('Consult Text', VXN_EXPRESS_ADDON_DOMAIN), 'value' => '[vxn_wa_text_consult]'],
                    ['text' => __('Product Consult Text', VXN_EXPRESS_ADDON_DOMAIN), 'value' => 'Konsul Produk'],
                    ['text' => __('Appointment Text', VXN_EXPRESS_ADDON_DOMAIN), 'value' => '[vxn_wa_text_appointment]'],
                    ['text' => 'Custom Text', 'value' => 'Custom'],
                ]
            ]),
            \Breakdance\Elements\control('custom_text', 'Custom text', [
                'type' => 'text',
                'layout' => 'vertical',
                'textOptions' => ['multiline' => true],
                'condition' => [
                    'path' => '%%CURRENTPATH%%.whatsapp_text',
                    'operand' => 'equals',
                    'value' => 'Custom'
                ]
            ]),
            \Breakdance\Elements\control('product_param', __('Product Name', VXN_EXPRESS_ADDON_DOMAIN), [
                'type' => 'text',
                'layout' => 'vertical',
                'textOptions' => ['multiline' => true],
                'condition' => [
                    'path' => '%%CURRENTPATH%%.whatsapp_text',
                    'operand' => 'is one of',
                    'value' => ['Order', 'Konsul Produk']
                ]
            ])
        ];        
    }

    /**
     * array $attributes
     */    
    public function handler($attributes): StringData
    {

        $text = $attributes['whatsapp_text'] ?? '';

        if($text === 'Custom'){
            $text = $attributes['custom_text'] ?? '';
            $text = Util::parse_shortcode_dynamic_var( $text );
        }elseif($text === 'Order'){
            $shortcode = sprintf('[vxn_wa_text_order product="%s"]', $attributes['product_param'] ?? '');
            $text = do_shortcode($shortcode);
        }elseif($text === 'Konsul Produk'){
            $shortcode = sprintf('[vxn_wa_text_consult_product product="%s"]', $attributes['product_param'] ?? '');
            $text = do_shortcode($shortcode);
        }else{
            $text = do_shortcode($text);
        }

        
        $whatsapp_url = do_shortcode( '[vxn_wa_url]' ) . '&text=' . urlencode($text);
        return StringData::fromString($whatsapp_url);        
    }
}
