<?php

use Phinx\Seed\AbstractSeed;
use Carbon\Carbon;
use App\Models\User;
use App\Models\MaintenanceType;
use App\Models\MaintenanceStatus;
use App\Models\MaintenancePriority;
use App\Models\MaintenancePlanTask;
use App\Models\MaintenancePlan;
use App\Models\Maintenance;
use App\Models\EquipmentType;
use App\Models\EquipmentLocation;
use App\Models\Equipment;

class TestSeeder extends AbstractSeed
{
    public function run()
    {
        // $this->insertCompany();
        // $this->insertRoles();
        // $this->insertUsers();
        // $this->insertSystems();
        // $this->insertProcesses();
        // $this->insertPolicies();
        // $this->insertNonconformityTypes();
        // $this->insertNonconformityTreatmentsTypes();
        // $this->insertComplaintCategories();
        // $this->insertActionSources();
        // $this->insertRiskLikelihoods();
        // $this->insertRiskConsequences();
        // $this->insertRiskLevels();
        // $this->insertRiskMatrix();
        $this->insertEquipments();
    }

    protected function insertCompany()
    {
        $data = [
            [
                'id'        => 1,
                'name'  => 'Empresa S.A.',
            ],
        ];

        $this->insert('company', $data);
    }

    protected function insertRoles()
    {
        $data = [
            [
                'id' => 1,
                'name' => 'Básico',
            ],
        ];

        $this->insert('role', $data);
    }

    protected function insertUsers()
    {
        $data = [
            [
                'id'        => 1,
                'username'  => 'admin',
                'password'  => password_hash('admin', PASSWORD_DEFAULT),
                'firstname' => 'Adminitrador',
                'is_active' => 1,
                'is_admin'  => 1,
                'password_expiration_date' => '2050-01-01'
            ],
            [
                'id'        => 2,
                'username'  => 'usuario',
                'password'  => password_hash('usuario', PASSWORD_DEFAULT),
                'firstname' => 'Usuario',
                'is_active' => 1,
                'is_admin'  => 0,
                'password_expiration_date' => '2050-01-01'
            ],
        ];

        $this->insert('user', $data);
    }

    protected function insertSystems()
    {
        $now = date('Y-m-d');

        $data = [
            [
                'name'       => 'Calidad',
                'created_at' => $now,
                'created_by' => 1
            ],
        ];

        $this->insert('system', $data);
    }

    protected function insertProcesses()
    {
        $now = date('Y-m-d');

        $data = [
            [
                'name'           => 'Ventas',
                'responsible_id' => 1,
                'is_active'      => 1,
                'created_at'     => $now,
                'created_by'     => 1
            ],
            [
                'name'           => 'Compras',
                'responsible_id' => 1,
                'is_active'      => 1,
                'created_at'     => $now,
                'created_by'     => 1
            ],
            [
                'name'           => 'Producción',
                'responsible_id' => 1,
                'is_active'      => 1,
                'created_at'     => $now,
                'created_by'     => 1
            ],
            [
                'name'           => 'Logistica',
                'responsible_id' => 1,
                'is_active'      => 1,
                'created_at'     => $now,
                'created_by'     => 1
            ],
        ];

        $this->insert('process', $data);
    }

    protected function insertPolicies()
    {
        $now = date('Y-m-d');

        $data = [
            [
                'description' => 'Mejorar continuamente los procesos',
                'created_at'  => $now,
                'created_by'  => 1
            ],
            [
                'description' => 'Satisfacer los requisitos de nuestros clientes',
                'created_at'  => $now,
                'created_by'  => 1
            ],
        ];

        $this->insert('policy', $data);
    }

    protected function insertNonconformityTypes()
    {
        $now = date('Y-m-d');

        $data = [
            [
                'description' => 'Servicio',
                'created_at'  => $now,
                'created_by'  => 1
            ],
            [
                'description' => 'Información',
                'created_at'  => $now,
                'created_by'  => 1
            ],
            [
                'description' => 'Producto',
                'created_at'  => $now,
                'created_by'  => 1
            ],
        ];

        $this->insert('nonconformity_type', $data);
    }

    protected function insertNonconformityTreatmentsTypes()
    {
        $now = date('Y-m-d');

        $data = [
            [
                'description' => 'Separación',
                'created_at'  => $now,
                'created_by'  => 1
            ],
            [
                'description' => 'Corrección',
                'created_at'  => $now,
                'created_by'  => 1
            ],
            [
                'description' => 'Contención',
                'created_at'  => $now,
                'created_by'  => 1
            ],
            [
                'description' => 'Devolución',
                'created_at'  => $now,
                'created_by'  => 1
            ],
            [
                'description' => 'Suspensión de provisión de productos y servicios',
                'created_at'  => $now,
                'created_by'  => 1
            ],
            [
                'description' => 'Información al cliente',
                'created_at'  => $now,
                'created_by'  => 1
            ],
            [
                'description' => 'Autorización bajo concesión',
                'created_at'  => $now,
                'created_by'  => 1
            ],
        ];

        $this->insert('nonconformity_treatment_type', $data);
    }

    protected function insertComplaintCategories()
    {
        $now = date('Y-m-d');

        $data = [
            [
                'description' => 'General',
                'created_at'  => $now,
                'created_by'  => 1
            ],
        ];

        $this->insert('complaint_category', $data);
    }

    protected function insertActionSources()
    {
        $now = date('Y-m-d');

        $data = [
            [
                'description' => 'Auditoria interna',
                'created_at'  => $now,
                'created_by'  => 1
            ],
            [
                'description' => 'Auditoria externa',
                'created_at'  => $now,
                'created_by'  => 1
            ],
            [
                'description' => 'No conformidad',
                'created_at'  => $now,
                'created_by'  => 1
            ],
        ];

        $this->insert('action_source', $data);
    }

    protected function insertRiskLikelihoods()
    {
        $now = date('Y-m-d');

        $data = [
            [
                'id'           => 1,
                'risk_type_id' => 1,
                'name'         => 'Nunca',
                'description'  => 'Nunca se presentara la fortaleza u oportunidad en la empresa',
                'value'        => 2,
                'created_at'   => $now,
                'created_by'   => 1
            ],
            [
                'id'           => 2,
                'risk_type_id' => 1,
                'name'         => 'Improbable',
                'description'  => 'Es improbable que se presente la fortaleza u oportunidad',
                'value'        => 2,
                'created_at'   => $now,
                'created_by'   => 1
            ],
            [
                'id'           => 3,
                'risk_type_id' => 1,
                'name'         => 'Ocasional',
                'description'  => 'Ocasionalmente se presentara la fortaleza u oportunidad',
                'value'        => 3,
                'created_at'   => $now,
                'created_by'   => 1
            ],
            [
                'id'           => 4,
                'risk_type_id' => 1,
                'name'         => 'Probable',
                'description'  => 'Es probable que se se presente la fortaleza u oportunidad',
                'value'        => 4,
                'created_at'   => $now,
                'created_by'   => 1
            ],
            [
                'id'           => 5,
                'risk_type_id' => 1,
                'name'         => 'Frecuente',
                'description'  => 'La fortaleza u oportunidad se presenta en forma frecuente o recurrente',
                'value'        => 5,
                'created_at'   => $now,
                'created_by'   => 1
            ],
            [
                'id'           => 6,
                'risk_type_id' => 2,
                'name'         => 'Nunca',
                'description'  => 'El hecho (debilidad o amenaza) no se presentará nunca',
                'value'        => 1,
                'created_at'   => $now,
                'created_by'   => 1
            ],
            [
                'id'           => 7,
                'risk_type_id' => 2,
                'name'         => 'Improbable',
                'description'  => 'El hecho (debilidad o amenaza) es improbable que ocurra',
                'value'        => 2,
                'created_at'   => $now,
                'created_by'   => 1
            ],
            [
                'id'           => 8,
                'risk_type_id' => 2,
                'name'         => 'Ocasional',
                'description'  => 'El hecho (debilidad o amenaza) se presentará en forma ocasional',
                'value'        => 3,
                'created_at'   => $now,
                'created_by'   => 1
            ],
            [
                'id'           => 9,
                'risk_type_id' => 2,
                'name'         => 'Probable',
                'description'  => 'Es probable que el hecho (debilidad o amenaza) se presente',
                'value'        => 4,
                'created_at'   => $now,
                'created_by'   => 1
            ],
            [
                'id'           => 10,
                'risk_type_id' => 2,
                'name'         => 'Frecuente',
                'description'  => 'El hecho (debilidad o amenaza) se presentará en forma frecuente',
                'value'        => 5,
                'created_at'   => $now,
                'created_by'   => 1
            ],
        ];

        $this->insert('risk_likelihood', $data);
    }

    protected function insertRiskConsequences()
    {
        $now = date('Y-m-d');

        $data = [
            [
                'id'           => 1,
                'risk_type_id' => 2,
                'name'         => 'Insignificante',
                'description'  => 'El hecho (debilidad o amenaza) es despreciable',
                'value'        => 1,
                'created_at'   => $now,
                'created_by'   => 1
            ],
            [
                'id'           => 2,
                'risk_type_id' => 2,
                'name'         => 'Marginal',
                'description'  => 'El hecho (debilidad o amenaza) es marginal en su consecuencia',
                'value'        => 2,
                'created_at'   => $now,
                'created_by'   => 1
            ],
            [
                'id'           => 3,
                'risk_type_id' => 2,
                'name'         => 'Crítico',
                'description'  => 'El hecho (debilidad o amenaza) es de consecuencias críticas para la organización, proceso, producto, servicio o cliente',
                'value'        => 3,
                'created_at'   => $now,
                'created_by'   => 1
            ],
            [
                'id'           => 4,
                'risk_type_id' => 2,
                'name'         => 'Severo',
                'description'  => 'El hecho (debilidad o amenaza) tiene consecuencis severas para la organización, proceso, producto, servicio o cliente',
                'value'        => 4,
                'created_at'   => $now,
                'created_by'   => 1
            ],
            [
                'id'           => 5,
                'risk_type_id' => 2,
                'name'         => 'Grave',
                'description'  => 'El hecho (debilidad o amenaza) tiene graves consecuencias para la organización, proceso, producto, servicio o cliente',
                'value'        => 5,
                'created_at'   => $now,
                'created_by'   => 1
            ],
            [
                'id'           => 6,
                'risk_type_id' => 1,
                'name'         => 'Insignificante',
                'description'  => 'El hecho (fortaleza u oportunidad) no tienen efecto para la organización, proceso, producto, servicio o cliente',
                'value'        => 1,
                'created_at'   => $now,
                'created_by'   => 1
            ],
            [
                'id'           => 7,
                'risk_type_id' => 1,
                'name'         => 'Mínimo',
                'description'  => 'El hecho (fortaleza u oportunidad) tienen una consecuencia marginal en su consecuencia',
                'value'        => 2,
                'created_at'   => $now,
                'created_by'   => 1
            ],
            [
                'id'           => 8,
                'risk_type_id' => 1,
                'name'         => 'Moderado',
                'description'  => 'El hecho (fortaleza u oportunidad) tiene una consecuencia destacada para la organización, proceso, producto, servicio o cliente',
                'value'        => 3,
                'created_at'   => $now,
                'created_by'   => 1
            ],
            [
                'id'           => 9,
                'risk_type_id' => 1,
                'name'         => 'Alta',
                'description'  => 'El hecho (fortaleza u oportunidad) tiene consecuencias importantes para la organización, proceso, producto, servicio o cliente ya que pueden generarse nuevos negocios',
                'value'        => 4,
                'created_at'   => $now,
                'created_by'   => 1
            ],
            [
                'id'           => 10,
                'risk_type_id' => 1,
                'name'         => 'Muy alta',
                'description'  => 'El hecho (fortaleza u oportunidad) tiene consecuencias MUY importantes para la organización, proceso, producto, servicio o cliente ya que se generan nuevos negocios',
                'value'        => 5,
                'created_at'   => $now,
                'created_by'   => 1
            ],
        ];

        $this->insert('risk_consequence', $data);
    }

    protected function insertRiskLevels()
    {
        $now = date('Y-m-d');

        $data = [
            [
                'id' => 1,
                'name' => 'Mínimo',
                'color' => '#aad356',
                'created_at'   => $now,
                'created_by'   => 1
            ],
            [
                'id' => 2,
                'name' => 'Bajo',
                'color' => '#35a7ff',
                'created_at'   => $now,
                'created_by'   => 1
            ],
            [
                'id' => 3,
                'name' => 'Medio',
                'color' => '#ffe74c',
                'created_at'   => $now,
                'created_by'   => 1
            ],
            [
                'id' => 4,
                'name' => 'Elevado',
                'color' => '#ffad00',
                'created_at'   => $now,
                'created_by'   => 1
            ],
            [
                'id' => 5,
                'name' => 'Grave',
                'color' => '#ff5964',
                'created_at'   => $now,
                'created_by'   => 1
            ],
        ];

        $this->insert('risk_level', $data);
    }

    protected function insertRiskMatrix()
    {
        $this->execute('
            INSERT INTO
                `risk_matrix` (`risk_type_id`, `risk_likelihood_id`, `risk_consequence_id`, `risk_level_id`)
            VALUES
                (1,	1,	6,	1),
                (1,	1,	7,	1),
                (1,	1,	8,	1),
                (1,	1,	9,	1),
                (1,	2,	6,	1),
                (1,	2,	7,	1),
                (1,	2,	8,	1),
                (1,	3,	6,	1),
                (1,	3,	7,	1),
                (1,	4,	6,	1),
                (2,	6,	1,	1),
                (2,	6,	2,	1),
                (2,	6,	3,	1),
                (2,	6,	4,	1),
                (2,	7,	1,	1),
                (2,	7,	2,	1),
                (2,	7,	3,	1),
                (2,	8,	1,	1),
                (2,	8,	2,	1),
                (2,	9,	1,	1),
                (1,	1,	10,	2),
                (1,	2,	9,	2),
                (1,	3,	8,	2),
                (1,	4,	7,	2),
                (1,	5,	6,	2),
                (2,	6,	5,	2),
                (2,	7,	4,	2),
                (2,	8,	3,	2),
                (2,	9,	2,	2),
                (2,	10,	1,	2),
                (1,	2,	10,	3),
                (1,	3,	9,	3),
                (1,	4,	8,	3),
                (1,	5,	7,	3),
                (2,	7,	5,	3),
                (2,	8,	4,	3),
                (2,	9,	3,	3),
                (2,	10,	2,	3),
                (1,	3,	10,	4),
                (1,	4,	9,	4),
                (1,	4,	10,	4),
                (1,	5,	8,	4),
                (1,	5,	9,	4),
                (1,	5,	10,	4),
                (2,	8,	5,	4),
                (2,	9,	4,	4),
                (2,	10,	3,	4),
                (2,	9,	5,	5),
                (2,	10,	4,	5),
                (2,	10,	5,	5)
        ');
    }

    protected function insertEquipments()
    {
        // $type = factory(EquipmentType::class, 10)->create([
        //     'created_by' => 1
        // ]);
        
        // $location = factory(EquipmentLocation::class, 5)->create([
        //     'created_by' => 1
        // ]);

        // $plans = factory(MaintenancePlan::class, 10)->create([
        //     'created_by' => 1,
        // ]);

        // $equipments = factory(Equipment::class, 50)->create([
        //     'created_by' => 1,
        // ]);

        $equipment = Equipment::find(53);
        $user = User::find(1);
        $initialStatus = MaintenanceStatus::defaultInitial();
        $finalStatus = MaintenanceStatus::defaultFinal();
        
        $dailyTask = factory(MaintenancePlanTask::class)->states('daily')->make();
        $weeklyTask = factory(MaintenancePlanTask::class)->states('weekly')->make();
        $monthlyTask = factory(MaintenancePlanTask::class)->states('monthly')->make();
        $quarterlyTask = factory(MaintenancePlanTask::class)->states('quarterly')->make();

        $equipment->plan->tasks()->saveMany([
            $monthlyTask,
            $quarterlyTask,
            $weeklyTask,
            $dailyTask,
        ]);

        $fromDate = Carbon::create(2018, 1, 1);
        $toDate = Carbon::create(2018, 6, 30);
        $firstDate = Carbon::create(2018, 1, 5);
        
        $tasks = $equipment->plan->tasks()->get();

        foreach ($tasks as $task) {
            $maintenance = new Maintenance;
            $maintenance->equipment_id = $equipment->id;
            $maintenance->type = MaintenanceType::PREVENTIVE;
            $maintenance->priority = MaintenancePriority::NORMAL;
            $maintenance->due_date = $firstDate;
            $maintenance->responsible_id = $user->id;
            $maintenance->maintenance_status_id = $initialStatus->id;
            $maintenance->setTask($task);
            $maintenance->setCreatedBy($user);
            
            for ($i = 0; $i < 3; $i++) {
                $maintenance->maintenance_status_id = $finalStatus->id;
                $maintenance->completed_at = $maintenance->due_date;
                $maintenance->completed_by = $user->id;
                $maintenance->save();
                
                $maintenance = $maintenance->createNextMaintenance($user);
            }
        }
    }
}
