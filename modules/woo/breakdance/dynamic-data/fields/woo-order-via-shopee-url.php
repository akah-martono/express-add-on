<?php
namespace VXN\Express\Woo\Breakdance\Dynamic_Data\Fields;

use Breakdance\DynamicData\StringData;
use Breakdance\DynamicData\StringField;

class Woo_Order_Via_Shopee_URL extends StringField
{
    /**
     * @inheritDoc
     */
    public function label()
    {
        return __('Order Via Shopee URL', VXN_EXPRESS_ADDON_DOMAIN);
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
        return 'vxn-woo-order-via-shopee';
    }

    public function returnTypes()
    {
        return ['url'];
    }

    public function handler($attributes): StringData
    {
        return StringData::fromString(do_shortcode('[vxn_woo_order_via_shopee_url]'));        
    }
}
