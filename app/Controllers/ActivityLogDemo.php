<?php

declare(strict_types=1);

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\ResponseInterface;
use GroceryCrud\GroceryCrud;

/**
 * Activity Log / Audit Trail Demo Controller.
 *
 * Demonstrates the Activity Log feature of Grocery CRUD:
 * - enableActivityLog() with user resolver
 * - setActivityLogFieldLabels() for human-readable field names
 * - setActivityLogExcludeFields() to exclude sensitive data
 * - View logs with action badges, old/new data diff
 *
 * Usage:
 *   1. Run migration: php spark migrate -n App
 *   2. Start server:  php spark serve
 *   3. Open browser:  http://localhost:8080/activity-log-demo
 */
class ActivityLogDemo extends Controller
{
    /**
     * Render navbar sebagai string.
     */
    private function renderNavbar(string $activePage = ''): string
    {
        return view('layouts/navbar', [
            'brandUrl'   => '/activity-log-demo',
            'brandIcon'  => 'bi-journal-text',
            'brandText'  => 'Activity Log <small class="fw-light">Demo</small>',
            'tabs'       => [
                'index'      => ['url' => '/activity-log-demo',          'icon' => 'bi-info-circle', 'label' => 'Overview'],
                'categories' => ['url' => '/activity-log-demo/categories','icon' => 'bi-bookmark',    'label' => 'CRUD Demo'],
                'logs'       => ['url' => '/activity-log-demo/logs',     'icon' => 'bi-list-check',  'label' => 'View Logs'],
            ],
            'activePage' => $activePage,
        ]);
    }

    /**
     * Main overview page — explains Activity Log / Audit Trail feature.
     */
    public function index(): string
    {
        return view('activity_log_demo/index');
    }

    /**
     * Categories CRUD with Activity Log enabled.
     */
    public function categories(): ResponseInterface|string
    {
        $crud = new GroceryCrud();
        $crud->setTable('categories', 'Category');

        $crud->setColumns('name', 'description', 'status', 'created_at');
        $crud->setFields('name', 'description', 'status');

        $crud->displayAs('name', 'Category Name');
        $crud->displayAs('description', 'Description');
        $crud->displayAs('created_at', 'Created');

        $crud->required('name');
        $crud->unique('name');

        $crud->setFieldType('status', 'dropdown', [
            'active'   => 'Active',
            'inactive' => 'Inactive',
        ]);

        $crud->setColumnFilter('name', 'text');
        $crud->setColumnFilter('status', 'dropdown', [
            'active'   => 'Active',
            'inactive' => 'Inactive',
        ]);

        $crud->setBatchAction('delete_selected', 'Delete Selected');
        $crud->setBatchAction('restore_selected', 'Restore Selected');

        $crud->orderBy('name', 'ASC');

        // ═══════════════════════════════════════
        // ACTIVITY LOG — the feature being demonstrated
        // ═══════════════════════════════════════
        $crud->enableActivityLog(function () {
            return [
                'id'   => session()->get('userId'),
                'name' => session()->get('fullName') ?: session()->get('username'),
            ];
        });

        $crud->setActivityLogFieldLabels([
            'name'        => 'Category Name',
            'description' => 'Description',
            'status'      => 'Status',
            'created_at'  => 'Created Date',
        ]);

        $crud->setTheme('bootstrap5');

        // ======== Activity Log Viewer UI (built-in) ========
        $crud->enableActivityLogViewer();

        return $crud->setPageHeader($this->renderNavbar('categories'))->render();
    }

    /**
     * View Activity Logs page.
     */
    public function logs(): string
    {
        $request      = service('request');
        $filterTable  = $request->getGet('table');
        $filterAction = $request->getGet('action');
        $search       = $request->getGet('search');
        $page         = max(1, (int) ($request->getGet('page') ?? 1));
        $perPage      = 25;

        $db      = db_connect();
        $builder = $db->table('activity_logs');

        if (!empty($filterTable)) {
            $builder->where('table_name', $filterTable);
        }
        if (!empty($filterAction)) {
            $builder->where('action', $filterAction);
        }
        if (!empty($search)) {
            $builder->groupStart()
                ->like('user_name', $search)
                ->orLike('record_pk', $search)
                ->orLike('table_name', $search)
                ->groupEnd();
        }

        $total      = $builder->countAllResults(false);
        $logs       = $builder->orderBy('created_at', 'DESC')
            ->limit($perPage, ($page - 1) * $perPage)
            ->get()
            ->getResultArray();
        $totalPages = max(1, (int) ceil($total / $perPage));

        $tables = $db->table('activity_logs')
            ->distinct()
            ->select('table_name')
            ->orderBy('table_name', 'ASC')
            ->get()
            ->getResultArray();

        $actionBadges = [
            'insert'  => '<span class="badge bg-success">INSERT</span>',
            'update'  => '<span class="badge bg-primary">UPDATE</span>',
            'delete'  => '<span class="badge bg-danger">DELETE</span>',
            'restore' => '<span class="badge bg-warning text-dark">RESTORE</span>',
            'import'  => '<span class="badge bg-info text-dark">IMPORT</span>',
        ];

        $optTable = '<option value="">All Tables</option>';
        foreach ($tables as $t) {
            $sel = $t['table_name'] === $filterTable ? ' selected' : '';
            $optTable .= '<option value="' . esc($t['table_name']) . '"' . $sel . '>'
                . esc($t['table_name']) . '</option>';
        }

        $actions   = ['insert', 'update', 'delete', 'restore', 'import'];
        $optAction = '<option value="">All Actions</option>';
        foreach ($actions as $a) {
            $sel = $a === $filterAction ? ' selected' : '';
            $optAction .= '<option value="' . $a . '"' . $sel . '>' . ucfirst($a) . '</option>';
        }

        $qParams = [];
        if (!empty($filterTable))  $qParams['table']  = $filterTable;
        if (!empty($filterAction)) $qParams['action']  = $filterAction;
        if (!empty($search))       $qParams['search']  = $search;
        $baseUrl = '/activity-log-demo/logs?' . http_build_query($qParams);
        if (!empty($qParams)) $baseUrl .= '&';

        return view('activity_log_demo/logs', [
            'logs'         => $logs,
            'total'        => $total,
            'totalPages'   => $totalPages,
            'page'         => $page,
            'baseUrl'      => $baseUrl,
            'optTable'     => $optTable,
            'optAction'    => $optAction,
            'searchEsc'    => esc((string) $search),
            'filterTable'  => $filterTable,
            'filterAction' => $filterAction,
            'actionBadges' => $actionBadges,
        ]);
    }
}
