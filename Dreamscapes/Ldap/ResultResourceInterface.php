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

/**
 * This interface prescribes which methods a class must implement to serve as object encapsulation of PHP's native ldap
 * result resource and related functions.
 *
 * @package Ldap-Core
 */
interface ResultResourceInterface
{
    /**
     * Create a new instance
     *
     * @param LinkResourceInterface $link
     * @param resource              $result
     */
    public function __construct(LinkResourceInterface $link, $result);

    /**
     * Retrieve the LDAP pagination cookie
     *
     * @return array    Array with two keys: 'cookie' and 'estimated'
     */
    public function pagedResultResponse();

    /**
     * Count the number of entries in a search
     *
     * @return int      Number of entries in the result
     */
    public function countEntries();

    /**
     * Free result memory
     *
     * Once the result is freed from memory the instance holding the result must not be used any further. This method
     * must be called automatically when the instance is released from memory.
     *
     * @return void
     */
    public function freeResult();

    /**
     * Get all result entries
     *
     * @return array    Complete result information in a multi-dimensional array
     */
    public function getEntries();

    /**
     * Extract information about referrals (if returned by server)
     *
     * @return array
     */
    public function getReferrals();

    /**
     * Sort LDAP result entries
     *
     * @param  string $sortFilter The attribute to use as a key in the sort
     * @return self
     */
    public function sort($sortFilter);
}
