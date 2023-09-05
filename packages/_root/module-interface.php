<?php
namespace VXN\Express;

/**
 * Module Interface
 * @package VXN\Express
 * @author Vaksin <dev@vaks.in>
 * @since 1.1
 */
interface Module_Interface {
    /** @return string  */
    public static function name();

    /** @return string  */
    public static function slug();

    /** @return void  */
    public function run();
}