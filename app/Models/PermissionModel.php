<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

class PermissionModel extends Model
{
    protected $table            = 'permissions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;

    protected $allowedFields = [
        'role',
        'table_name',
        'can_view',
        'can_add',
        'can_edit',
        'can_delete',
        'can_export',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get permissions for a specific role.
     *
     * @return array<string, array<string, bool>> [table_name => [action => bool]]
     */
    public function getRolePermissions(string $role): array
    {
        $rows = $this->where('role', $role)->findAll();
        $permissions = [];

        foreach ($rows as $row) {
            $permissions[$row['table_name']] = [
                'view'   => (bool) $row['can_view'],
                'add'    => (bool) $row['can_add'],
                'edit'   => (bool) $row['can_edit'],
                'delete' => (bool) $row['can_delete'],
                'export' => (bool) $row['can_export'],
            ];
        }

        return $permissions;
    }

    /**
     * Get actions allowed for a specific role and table.
     *
     * @return string[] List of allowed actions (add, edit, delete, view, export)
     */
    public function getAllowedActions(string $role, string $tableName): array
    {
        $perms = $this->getRolePermissions($role);
        $tablePerms = $perms[$tableName] ?? [];
        $actions = [];

        foreach ($tablePerms as $action => $allowed) {
            if ($allowed) {
                $actions[] = $action;
            }
        }

        return $actions;
    }
}
