<?php
namespace VXN\Express\Woo\Breakdance\Dynamic_Data\Fields;

use Breakdance\DynamicData\StringData;
use Breakdance\DynamicData\StringField;
use VXN\Express;

class Show_Whatsapp_On_Product extends StringField
{
    /**
     * @inheritDoc
     */
    public function label()
    {
        return __('Is Show WhatsApp Button On Product', VXN_EXPRESS_ADDON_DOMAIN);
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
        return 'vxn-woo-show-whatsapp-on-product';
    }

    public function returnTypes()
    {
        return ['string'];
    }

    public function handler($attributes): StringData
    {
        return StringData::fromString(Express::Options('vxn_express_woo.chk_order_via_wa_on_product', 0));        
    }
}
