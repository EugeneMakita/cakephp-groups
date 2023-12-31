<?php
/**
 * Copyright (c) Qobo Ltd. (https://www.qobo.biz)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Qobo Ltd. (https://www.qobo.biz)
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace Groups\Shell;

use Cake\Console\ConsoleOptionParser;
use Cake\Console\Shell;

class GroupShell extends Shell
{
    /**
     * {@inheritDoc}
     */
    public $tasks = [
        'Groups.Assign',
        'Groups.Import',
        'Groups.UserGroupCleanup',
        'Groups.SyncLdapGroups'
    ];

    /**
     * {@inheritDoc}
     */
    public function getOptionParser()
    {
        $parser = parent::getOptionParser();

        $parser
            ->description('Groups Shell that handle\'s related tasks.')
            ->addSubcommand(
                'assign',
                ['help' => 'Assign group to all users.', 'parser' => $this->Assign->getOptionParser()]
            )
            ->addSubcommand(
                'import',
                ['help' => 'Import system groups.', 'parser' => $this->Import->getOptionParser()]
            )
            ->addSubcommand(
                'user_group_cleanup',
                ['help' => 'User group clean up.', 'parser' => $this->UserGroupCleanup->getOptionParser()]
            )
            ->addSubcommand(
                'sync_ldap_groups',
                ['help' => 'LDAP groups synchronization.', 'parser' => $this->SyncLdapGroups->getOptionParser()]
            );

        return $parser;
    }
}
