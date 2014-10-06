<?php

/**
 * Dreamscapes/Ldap-Core
 *
 * Licensed under the BSD (3-Clause) license
 * For full copyright and license information, please see the LICENSE file
 *
 * @author      Robert Rossmann <rr.rossmann@me.com>
 * @copyright   2014 Robert Rossmann
 * @link        https://github.com/Dreamscapes/Ldap-Core
 * @license     http://choosealicense.com/licenses/bsd-3-clause   BSD (3-Clause) License
 */


namespace Dreamscapes\Ldap;

use Dreamscapes\Ldap\Core\Ldap;

/**
 * Custom exception that represents an error from PHP's native ldap interface
 *
 * @package Ldap-Core
 */
class LdapException extends \Exception
{
    /**
     * Custom constructor method
     *
     * The error code and message are extracted from the Ldap instance.
     *
     * @param Ldap $res
     */
    public function __construct(Ldap $res)
    {
        parent::__construct($res->error(), $res->errno());
    }
}
