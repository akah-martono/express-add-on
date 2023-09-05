<?php
namespace VXN\Express\Woo\Breakdance\Dynamic_Data\Fields;

use Breakdance\DynamicData\StringData;
use Breakdance\DynamicData\StringField;

class Woo_Whatsapp_Url extends StringField
{
    /**
     * @inheritDoc
     */
    public function label()
    {
        return __('Woo WhatsApp URL', VXN_EXPRESS_ADDON_DOMAIN);
    }

    /**
     * @inheritDoc
     */
    public function category()
    {
        return 'Express WooCommerce';
    }

    /**
     * @inheritDoc
     */
    public function slug()
    {
        return 'vxn-woo-whatsapp-url';
    }

    /**
     * @inheritDoc
     */    
    public function returnTypes()
    {
        return ['url', 'string'];
    }

    /**
     * @inheritDoc
     */        
    public function controls()
    {
        return [
            \Breakdance\Elements\control('woo_whatsapp_text', 'Template', [
                'type' => 'dropdown',
                'layout' => 'vertical',
                'items' => [
                    ['text' => 'Order Text', 'value' => '[vxn_wa_wo_text_order]'],
                    ['text' => 'Consult Text', 'value' => '[vxn_wa_wo_text_consult]'],
                ]
            ])
        ];        
    }

    /**
     * array $attributes
     */    
    public function handler($attributes): StringData
    {
        $text = do_shortcode($attributes['woo_whatsapp_text'] ?? '');
        $whatsapp_url = do_shortcode( '[vxn_wa_url]' ) . '&text=' . urlencode($text);
        return StringData::fromString($whatsapp_url);        
    }
}
