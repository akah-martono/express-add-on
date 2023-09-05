<?php
namespace VXN\Express\Woo\Breakdance\Dynamic_Data\Fields;

use Breakdance\DynamicData\StringData;
use Breakdance\DynamicData\StringField;
use VXN\Express;

class Woo_Order_Via_Whatsapp extends StringField
{
    /**
     * @inheritDoc
     */
    public function label()
    {
        return __('Order Via WhatsApp Text', VXN_EXPRESS_ADDON_DOMAIN);
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
        return 'vxn-woo-order-via-whatsapp';
    }

    public function returnTypes()
    {
        return ['string'];
    }

    public function handler($attributes): StringData
    {
        return StringData::fromString(Express::Options('vxn_express_woo.txt_order_via_wa_text', '') );
    }
}
