<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_341 extends CI_Migration
{
    public function up(): void
    {
        $prefix = db_prefix();

        // 1. Ensure Staff exists (demo@admin.com / 123456)
        $staff_email = 'demo@admin.com';
        $password_hash = password_hash('123456', PASSWORD_BCRYPT);
        
        $this->db->where('email', $staff_email);
        $staff = $this->db->get($prefix . 'staff')->row();
        
        if (!$staff) {
            $this->db->insert($prefix . 'staff', [
                'firstname' => 'Demo',
                'lastname' => 'Admin',
                'email' => $staff_email,
                'password' => $password_hash,
                'admin' => 1,
                'active' => 1,
                'datecreated' => date('Y-m-d H:i:s')
            ]);
            $staff_id = $this->db->insert_id();
        } else {
            $this->db->where('staffid', $staff->staffid);
            $this->db->update($prefix . 'staff', [
                'password' => $password_hash,
                'active' => 1,
                'admin' => 1
            ]);
            $staff_id = $staff->staffid;
        }

        // 2. Ensure Client & Contact exists (demo@client.com / 123456)
        $client_email = 'demo@client.com';
        
        $this->db->where('email', $client_email);
        $contact = $this->db->get($prefix . 'contacts')->row();
        
        if (!$contact) {
            // Create Client
            $this->db->insert($prefix . 'clients', [
                'company' => 'Demo Client Org',
                'datecreated' => date('Y-m-d H:i:s'),
                'active' => 1,
                'registration_confirmed' => 1
            ]);
            $client_id = $this->db->insert_id();

            // Create Contact
            $this->db->insert($prefix . 'contacts', [
                'userid' => $client_id,
                'firstname' => 'Demo',
                'lastname' => 'Client',
                'email' => $client_email,
                'password' => $password_hash,
                'is_primary' => 1,
                'active' => 1,
                'datecreated' => date('Y-m-d H:i:s'),
                'email_verified_at' => date('Y-m-d H:i:s')
            ]);
            $contact_id = $this->db->insert_id();
        } else {
            $client_id = $contact->userid;
            $contact_id = $contact->id;
            
            $this->db->where('id', $contact_id);
            $this->db->update($prefix . 'contacts', [
                'password' => $password_hash,
                'active' => 1,
                'email_verified_at' => date('Y-m-d H:i:s')
            ]);

            $this->db->where('userid', $client_id);
            $this->db->update($prefix . 'clients', [
                'active' => 1,
                'registration_confirmed' => 1
            ]);
        }

        // 3. Departments
        $this->db->where('name', 'Sales');
        if ($this->db->count_all_results($prefix . 'departments') == 0) {
            $this->db->insert($prefix . 'departments', ['name' => 'Sales', 'calendar_id' => '']);
        }
        $this->db->where('name', 'Support');
        if ($this->db->count_all_results($prefix . 'departments') == 0) {
            $this->db->insert($prefix . 'departments', ['name' => 'Support', 'calendar_id' => '']);
        }
        $dep = $this->db->get($prefix . 'departments')->row();
        $department_id = $dep ? $dep->departmentid : 1;

        // 4. Lead Statuses & Sources (Ensure at least one exists)
        $this->db->limit(1);
        $lead_status = $this->db->get($prefix . 'leads_status')->row();
        if (!$lead_status) {
            $this->db->insert($prefix . 'leads_status', ['name' => 'New', 'statusorder' => 1, 'color' => '#757575']);
            $lead_status_id = $this->db->insert_id();
        } else {
            $lead_status_id = $lead_status->id;
        }

        $this->db->limit(1);
        $lead_source = $this->db->get($prefix . 'leads_sources')->row();
        if (!$lead_source) {
            $this->db->insert($prefix . 'leads_sources', ['name' => 'Google']);
            $lead_source_id = $this->db->insert_id();
        } else {
            $lead_source_id = $lead_source->id;
        }

        // 5. Populate Dummy Leads if table has < 2 records
        if ($this->db->count_all($prefix . 'leads') < 2) {
            $leads = [
                [
                    'name' => 'John Doe',
                    'title' => 'CEO',
                    'company' => 'Acme Corp',
                    'email' => 'john@acme.com',
                    'phonenumber' => '+15550199',
                    'status' => $lead_status_id,
                    'source' => $lead_source_id,
                    'description' => 'Interested in CRM modules.',
                    'dateadded' => date('Y-m-d H:i:s'),
                    'addedfrom' => $staff_id,
                    'assigned' => $staff_id
                ],
                [
                    'name' => 'Jane Smith',
                    'title' => 'Marketing Manager',
                    'company' => 'Stark Industries',
                    'email' => 'jane@stark.com',
                    'phonenumber' => '+15550288',
                    'status' => $lead_status_id,
                    'source' => $lead_source_id,
                    'description' => 'Follow up next week.',
                    'dateadded' => date('Y-m-d H:i:s'),
                    'addedfrom' => $staff_id,
                    'assigned' => $staff_id
                ]
            ];
            foreach ($leads as $l) {
                $this->db->insert($prefix . 'leads', $l);
            }
        }

        // 6. Populate Dummy Projects if table has < 2 records
        if ($this->db->count_all($prefix . 'projects') < 2) {
            $projects = [
                [
                    'name' => 'Website Redesign',
                    'description' => 'Migrate and upgrade the main marketing site.',
                    'status' => 2, // In Progress
                    'clientid' => $client_id,
                    'start_date' => date('Y-m-d'),
                    'deadline' => date('Y-m-d', strtotime('+30 days')),
                    'project_created' => date('Y-m-d'),
                    'addedfrom' => $staff_id
                ],
                [
                    'name' => 'Mobile App Integration',
                    'description' => 'Integrate Flutex Admin mobile application.',
                    'status' => 2, // In Progress
                    'clientid' => $client_id,
                    'start_date' => date('Y-m-d'),
                    'deadline' => date('Y-m-d', strtotime('+60 days')),
                    'project_created' => date('Y-m-d'),
                    'addedfrom' => $staff_id
                ]
            ];
            foreach ($projects as $p) {
                $this->db->insert($prefix . 'projects', $p);
            }
        }
        $proj = $this->db->get($prefix . 'projects')->row();
        $project_id = $proj ? $proj->id : 1;

        // 7. Populate Dummy Tasks if table has < 2 records
        if ($this->db->count_all($prefix . 'tasks') < 2) {
            $tasks = [
                [
                    'name' => 'Setup development server',
                    'description' => 'Configure webserver and database pools.',
                    'status' => 4, // In Progress
                    'priority' => 3, // High
                    'startdate' => date('Y-m-d'),
                    'duedate' => date('Y-m-d', strtotime('+7 days')),
                    'rel_id' => $project_id,
                    'rel_type' => 'project',
                    'dateadded' => date('Y-m-d H:i:s'),
                    'addedfrom' => $staff_id
                ],
                [
                    'name' => 'Design homepage layout',
                    'description' => 'Create mockup wireframe options.',
                    'status' => 1, // Not Started
                    'priority' => 2, // Medium
                    'startdate' => date('Y-m-d'),
                    'duedate' => date('Y-m-d', strtotime('+14 days')),
                    'rel_id' => $project_id,
                    'rel_type' => 'project',
                    'dateadded' => date('Y-m-d H:i:s'),
                    'addedfrom' => $staff_id
                ]
            ];
            foreach ($tasks as $t) {
                $this->db->insert($prefix . 'tasks', $t);
            }
        }

        // 8. Populate Dummy Proposals if table has < 2 records
        if ($this->db->count_all($prefix . 'proposals') < 2) {
            $proposals = [
                [
                    'subject' => 'CRM Setup & Training Proposal',
                    'content' => 'Implementation details for CRM setup and team training.',
                    'addedfrom' => $staff_id,
                    'datecreated' => date('Y-m-d H:i:s'),
                    'open_till' => date('Y-m-d', strtotime('+15 days')),
                    'date' => date('Y-m-d'),
                    'rel_id' => $client_id,
                    'rel_type' => 'customer',
                    'status' => 1, // Open
                    'total' => 1200.00,
                    'subtotal' => 1200.00,
                    'currency' => 1
                ],
                [
                    'subject' => 'Custom API Integration Upgrade',
                    'content' => 'Proposal for custom database migration and API bridges.',
                    'addedfrom' => $staff_id,
                    'datecreated' => date('Y-m-d H:i:s'),
                    'open_till' => date('Y-m-d', strtotime('+15 days')),
                    'date' => date('Y-m-d'),
                    'rel_id' => $client_id,
                    'rel_type' => 'customer',
                    'status' => 1, // Open
                    'total' => 2500.00,
                    'subtotal' => 2500.00,
                    'currency' => 1
                ]
            ];
            foreach ($proposals as $pr) {
                $this->db->insert($prefix . 'proposals', $pr);
            }
        }

        // 9. Populate Dummy Estimates if table has < 2 records
        if ($this->db->count_all($prefix . 'estimates') < 2) {
            $estimates = [
                [
                    'clientid' => $client_id,
                    'number' => 1,
                    'date' => date('Y-m-d'),
                    'expirydate' => date('Y-m-d', strtotime('+30 days')),
                    'subtotal' => 500.00,
                    'total' => 500.00,
                    'status' => 1, // Sent
                    'currency' => 1,
                    'datecreated' => date('Y-m-d H:i:s'),
                    'addedfrom' => $staff_id
                ]
            ];
            foreach ($estimates as $e) {
                $this->db->insert($prefix . 'estimates', $e);
            }
        }

        // 10. Populate Dummy Invoices if table has < 2 records
        if ($this->db->count_all($prefix . 'invoices') < 2) {
            $invoices = [
                [
                    'clientid' => $client_id,
                    'number' => 1,
                    'date' => date('Y-m-d'),
                    'duedate' => date('Y-m-d', strtotime('+30 days')),
                    'subtotal' => 850.00,
                    'total' => 850.00,
                    'status' => 1, // Unpaid
                    'currency' => 1,
                    'datecreated' => date('Y-m-d H:i:s'),
                    'addedfrom' => $staff_id,
                    'hash' => app_generate_hash()
                ]
            ];
            foreach ($invoices as $i) {
                $this->db->insert($prefix . 'invoices', $i);
            }
        }

        // 11. Populate Dummy Expenses if table has < 2 records
        if ($this->db->count_all($prefix . 'expenses') < 2) {
            // Ensure at least one expense category exists
            $this->db->limit(1);
            $exp_cat = $this->db->get($prefix . 'expenses_categories')->row();
            if (!$exp_cat) {
                $this->db->insert($prefix . 'expenses_categories', ['name' => 'Hosting & Servers']);
                $exp_cat_id = $this->db->insert_id();
            } else {
                $exp_cat_id = $exp_cat->id;
            }

            $expenses = [
                [
                    'category' => $exp_cat_id,
                    'amount' => 45.00,
                    'date' => date('Y-m-d'),
                    'addedfrom' => $staff_id,
                    'currency' => 1,
                    'note' => 'CPanel VPS Hosting Monthly Subscription'
                ]
            ];
            foreach ($expenses as $ex) {
                $this->db->insert($prefix . 'expenses', $ex);
            }
        }

        // 12. Populate Dummy Contracts if table has < 2 records
        if ($this->db->count_all($prefix . 'contracts') < 2) {
            $contracts = [
                [
                    'client' => $client_id,
                    'subject' => 'Service Level Agreement',
                    'contract_value' => 3000.00,
                    'datestart' => date('Y-m-d'),
                    'dateend' => date('Y-m-d', strtotime('+365 days')),
                    'dateadded' => date('Y-m-d H:i:s'),
                    'addedfrom' => $staff_id
                ]
            ];
            foreach ($contracts as $c) {
                $this->db->insert($prefix . 'contracts', $c);
            }
        }

        // 13. Populate Dummy Tickets if table has < 2 records
        if ($this->db->count_all($prefix . 'tickets') < 2) {
            $tickets = [
                [
                    'admin' => null,
                    'userid' => $client_id,
                    'contactid' => $contact_id,
                    'email' => $client_email,
                    'name' => 'Demo Client',
                    'department' => $department_id,
                    'priority' => 2, // Medium
                    'status' => 1, // Open
                    'subject' => 'How to configure custom email template?',
                    'message' => 'I cannot find the configuration settings under system setup.',
                    'date' => date('Y-m-d H:i:s')
                ]
            ];
            foreach ($tickets as $t) {
                $this->db->insert($prefix . 'tickets', $t);
            }
        }
    }
}
