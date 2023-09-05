<?php
namespace VXN\Express\Woo\Breakdance\Dynamic_Data\Fields;

use Breakdance\DynamicData\StringData;
use Breakdance\DynamicData\StringField;

class Woo_Order_Via_Tokopedia_URL extends StringField
{
    /**
     * @inheritDoc
     */
    public function label()
    {
        return __('Order Via Tokopedia URL', VXN_EXPRESS_ADDON_DOMAIN);
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
        return 'vxn-woo-order-via-tokopedia';
    }

    public function returnTypes()
    {
        return ['url'];
    }

    public function handler($attributes): StringData
    {
        return StringData::fromString(do_shortcode('[vxn_woo_order_via_tokopedia_url]'));        
    }
}
