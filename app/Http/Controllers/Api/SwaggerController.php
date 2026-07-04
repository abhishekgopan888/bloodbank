<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

class SwaggerController extends Controller
{
    /**
     * Generate OpenAPI specification from your actual routes
     */
    public function spec()
    {
        $spec = [
            'openapi' => '3.0.0',
            'info' => [
                'title' => 'Blood Bank API',
                'description' => 'Blood Bank Management System API',
                'version' => '1.0.0',
                'contact' => [
                    'name' => 'API Support',
                    'email' => 'support@bloodbank.local',
                ],
            ],
            'servers' => [
                ['url' => url('/api'), 'description' => 'API Server'],
            ],
            'components' => [
                'securitySchemes' => [
                    'sanctum' => [
                        'type' => 'http',
                        'scheme' => 'bearer',
                        'bearerFormat' => 'laravel-sanctum',
                    ],
                ],
                'schemas' => [
                    'User' => [
                        'type' => 'object',
                        'properties' => [
                            'id' => ['type' => 'integer'],
                            'name' => ['type' => 'string'],
                            'email' => ['type' => 'string', 'format' => 'email'],
                            'role' => ['type' => 'string', 'enum' => ['admin', 'staff', 'viewer']],
                            'created_at' => ['type' => 'string', 'format' => 'date-time'],
                        ],
                    ],
                    'BloodBag' => [
                        'type' => 'object',
                        'properties' => [
                            'id' => ['type' => 'integer'],
                            'bag_number' => ['type' => 'string'],
                            'blood_type' => ['type' => 'string', 'enum' => ['O+', 'O-', 'A+', 'A-', 'B+', 'B-', 'AB+', 'AB-']],
                            'collection_date' => ['type' => 'string', 'format' => 'date'],
                            'expiry_date' => ['type' => 'string', 'format' => 'date'],
                            'refrigerator_id' => ['type' => 'integer'],
                            'created_at' => ['type' => 'string', 'format' => 'date-time'],
                        ],
                    ],
                    'Refrigerator' => [
                        'type' => 'object',
                        'properties' => [
                            'id' => ['type' => 'integer'],
                            'name' => ['type' => 'string'],
                            'model' => ['type' => 'string'],
                            'blood_bank_id' => ['type' => 'integer'],
                            'min_temp' => ['type' => 'number'],
                            'max_temp' => ['type' => 'number'],
                            'created_at' => ['type' => 'string', 'format' => 'date-time'],
                        ],
                    ],
                    'BloodBank' => [
                        'type' => 'object',
                        'properties' => [
                            'id' => ['type' => 'integer'],
                            'name' => ['type' => 'string'],
                            'location' => ['type' => 'string'],
                            'phone' => ['type' => 'string'],
                            'email' => ['type' => 'string', 'format' => 'email'],
                            'created_at' => ['type' => 'string', 'format' => 'date-time'],
                        ],
                    ],
                    'Alert' => [
                        'type' => 'object',
                        'properties' => [
                            'id' => ['type' => 'integer'],
                            'type' => ['type' => 'string', 'enum' => ['critical_temperature', 'expiry_warning', 'low_stock']],
                            'status' => ['type' => 'string', 'enum' => ['active', 'resolved']],
                            'message' => ['type' => 'string'],
                            'severity' => ['type' => 'string', 'enum' => ['low', 'medium', 'high', 'critical']],
                            'created_at' => ['type' => 'string', 'format' => 'date-time'],
                        ],
                    ],
                    'TemperatureLog' => [
                        'type' => 'object',
                        'properties' => [
                            'id' => ['type' => 'integer'],
                            'refrigerator_id' => ['type' => 'integer'],
                            'temperature' => ['type' => 'number'],
                            'humidity' => ['type' => 'number'],
                            'status' => ['type' => 'string', 'enum' => ['normal', 'warning', 'critical']],
                            'created_at' => ['type' => 'string', 'format' => 'date-time'],
                        ],
                    ],
                ],
            ],
            'security' => [
                ['sanctum' => []],
            ],
            'paths' => $this->generatePaths(),
        ];

        return response()->json($spec);
    }

    private function generatePaths()
    {
        return [
            '/login' => [
                'post' => [
                    'tags' => ['Authentication'],
                    'summary' => 'Login user',
                    'description' => 'Authenticate with email and password to receive a Sanctum token',
                    'security' => [],
                    'requestBody' => [
                        'required' => true,
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'required' => ['email', 'password'],
                                    'properties' => [
                                        'email' => ['type' => 'string', 'format' => 'email'],
                                        'password' => ['type' => 'string', 'format' => 'password'],
                                    ],
                                ],
                                'example' => [
                                    'email' => 'user@example.com',
                                    'password' => 'password123',
                                ],
                            ],
                        ],
                    ],
                    'responses' => [
                        '200' => [
                            'description' => 'Login successful',
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type' => 'object',
                                        'properties' => [
                                            'token' => ['type' => 'string'],
                                            'user' => ['$ref' => '#/components/schemas/User'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        '401' => ['description' => 'Invalid credentials'],
                    ],
                ],
            ],
            '/logout' => [
                'post' => [
                    'tags' => ['Authentication'],
                    'summary' => 'Logout user',
                    'description' => 'Logout and invalidate the current token',
                    'responses' => [
                        '200' => ['description' => 'Logged out successfully'],
                    ],
                ],
            ],
            '/blood-bags' => [
                'get' => [
                    'tags' => ['Blood Bags'],
                    'summary' => 'List all blood bags',
                    'parameters' => [
                        [
                            'name' => 'page',
                            'in' => 'query',
                            'description' => 'Page number for pagination',
                            'schema' => ['type' => 'integer'],
                        ],
                    ],
                    'responses' => [
                        '200' => [
                            'description' => 'List of blood bags',
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type' => 'object',
                                        'properties' => [
                                            'data' => [
                                                'type' => 'array',
                                                'items' => ['$ref' => '#/components/schemas/BloodBag'],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'post' => [
                    'tags' => ['Blood Bags'],
                    'summary' => 'Create a new blood bag',
                    'requestBody' => [
                        'required' => true,
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'required' => ['bag_number', 'blood_type', 'collection_date', 'expiry_date', 'refrigerator_id'],
                                    'properties' => [
                                        'bag_number' => ['type' => 'string'],
                                        'blood_type' => ['type' => 'string'],
                                        'collection_date' => ['type' => 'string', 'format' => 'date'],
                                        'expiry_date' => ['type' => 'string', 'format' => 'date'],
                                        'refrigerator_id' => ['type' => 'integer'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'responses' => [
                        '201' => [
                            'description' => 'Blood bag created',
                            'content' => [
                                'application/json' => [
                                    'schema' => ['$ref' => '#/components/schemas/BloodBag'],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            '/blood-bags/{id}' => [
                'get' => [
                    'tags' => ['Blood Bags'],
                    'summary' => 'Get a blood bag',
                    'parameters' => [
                        ['name' => 'id', 'in' => 'path', 'required' => true, 'schema' => ['type' => 'integer']],
                    ],
                    'responses' => [
                        '200' => [
                            'description' => 'Blood bag details',
                            'content' => [
                                'application/json' => [
                                    'schema' => ['$ref' => '#/components/schemas/BloodBag'],
                                ],
                            ],
                        ],
                    ],
                ],
                'put' => [
                    'tags' => ['Blood Bags'],
                    'summary' => 'Update a blood bag',
                    'parameters' => [
                        ['name' => 'id', 'in' => 'path', 'required' => true, 'schema' => ['type' => 'integer']],
                    ],
                    'requestBody' => [
                        'required' => true,
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'bag_number' => ['type' => 'string'],
                                        'blood_type' => ['type' => 'string'],
                                        'collection_date' => ['type' => 'string', 'format' => 'date'],
                                        'expiry_date' => ['type' => 'string', 'format' => 'date'],
                                        'refrigerator_id' => ['type' => 'integer'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'responses' => [
                        '200' => [
                            'description' => 'Blood bag updated',
                            'content' => [
                                'application/json' => [
                                    'schema' => ['$ref' => '#/components/schemas/BloodBag'],
                                ],
                            ],
                        ],
                    ],
                ],
                'delete' => [
                    'tags' => ['Blood Bags'],
                    'summary' => 'Delete a blood bag',
                    'parameters' => [
                        ['name' => 'id', 'in' => 'path', 'required' => true, 'schema' => ['type' => 'integer']],
                    ],
                    'responses' => [
                        '200' => ['description' => 'Blood bag deleted'],
                    ],
                ],
            ],
            '/blood-bags-expiring' => [
                'get' => [
                    'tags' => ['Blood Bags'],
                    'summary' => 'Get blood bags expiring within 1 day',
                    'responses' => [
                        '200' => [
                            'description' => 'List of expiring blood bags',
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type' => 'array',
                                        'items' => ['$ref' => '#/components/schemas/BloodBag'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            '/blood-bags-expired' => [
                'get' => [
                    'tags' => ['Blood Bags'],
                    'summary' => 'Get expired blood bags',
                    'responses' => [
                        '200' => [
                            'description' => 'List of expired blood bags',
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type' => 'array',
                                        'items' => ['$ref' => '#/components/schemas/BloodBag'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            '/blood-bags-near-risk-percentage' => [
                'get' => [
                    'tags' => ['Blood Bags'],
                    'summary' => 'Get percentage of blood bags near expiry',
                    'responses' => [
                        '200' => [
                            'description' => 'Risk percentage',
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type' => 'object',
                                        'properties' => [
                                            'near_risk_percentage' => ['type' => 'number'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            '/refrigerators' => [
                'get' => [
                    'tags' => ['Refrigerators'],
                    'summary' => 'List all refrigerators',
                    'parameters' => [
                        [
                            'name' => 'page',
                            'in' => 'query',
                            'schema' => ['type' => 'integer'],
                        ],
                    ],
                    'responses' => [
                        '200' => [
                            'description' => 'List of refrigerators',
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type' => 'object',
                                        'properties' => [
                                            'data' => [
                                                'type' => 'array',
                                                'items' => ['$ref' => '#/components/schemas/Refrigerator'],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'post' => [
                    'tags' => ['Refrigerators'],
                    'summary' => 'Create a new refrigerator',
                    'requestBody' => [
                        'required' => true,
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'required' => ['name', 'model', 'blood_bank_id', 'min_temp', 'max_temp'],
                                    'properties' => [
                                        'name' => ['type' => 'string'],
                                        'model' => ['type' => 'string'],
                                        'blood_bank_id' => ['type' => 'integer'],
                                        'min_temp' => ['type' => 'number'],
                                        'max_temp' => ['type' => 'number'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'responses' => [
                        '201' => [
                            'description' => 'Refrigerator created',
                            'content' => [
                                'application/json' => [
                                    'schema' => ['$ref' => '#/components/schemas/Refrigerator'],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            '/refrigerators/{id}' => [
                'get' => [
                    'tags' => ['Refrigerators'],
                    'summary' => 'Get a refrigerator',
                    'parameters' => [
                        ['name' => 'id', 'in' => 'path', 'required' => true, 'schema' => ['type' => 'integer']],
                    ],
                    'responses' => [
                        '200' => [
                            'description' => 'Refrigerator details',
                            'content' => [
                                'application/json' => [
                                    'schema' => ['$ref' => '#/components/schemas/Refrigerator'],
                                ],
                            ],
                        ],
                    ],
                ],
                'put' => [
                    'tags' => ['Refrigerators'],
                    'summary' => 'Update a refrigerator',
                    'parameters' => [
                        ['name' => 'id', 'in' => 'path', 'required' => true, 'schema' => ['type' => 'integer']],
                    ],
                    'requestBody' => [
                        'required' => true,
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'name' => ['type' => 'string'],
                                        'model' => ['type' => 'string'],
                                        'blood_bank_id' => ['type' => 'integer'],
                                        'min_temp' => ['type' => 'number'],
                                        'max_temp' => ['type' => 'number'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'responses' => [
                        '200' => [
                            'description' => 'Refrigerator updated',
                            'content' => [
                                'application/json' => [
                                    'schema' => ['$ref' => '#/components/schemas/Refrigerator'],
                                ],
                            ],
                        ],
                    ],
                ],
                'delete' => [
                    'tags' => ['Refrigerators'],
                    'summary' => 'Delete a refrigerator',
                    'parameters' => [
                        ['name' => 'id', 'in' => 'path', 'required' => true, 'schema' => ['type' => 'integer']],
                    ],
                    'responses' => [
                        '200' => ['description' => 'Refrigerator deleted'],
                    ],
                ],
            ],
            '/refrigerators/{id}/temperature-logs' => [
                'post' => [
                    'tags' => ['Temperature Logs'],
                    'summary' => 'Create a temperature log',
                    'parameters' => [
                        ['name' => 'id', 'in' => 'path', 'required' => true, 'schema' => ['type' => 'integer']],
                    ],
                    'requestBody' => [
                        'required' => true,
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'required' => ['temperature'],
                                    'properties' => [
                                        'temperature' => ['type' => 'number'],
                                        'humidity' => ['type' => 'number'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'responses' => [
                        '201' => [
                            'description' => 'Temperature log created',
                            'content' => [
                                'application/json' => [
                                    'schema' => ['$ref' => '#/components/schemas/TemperatureLog'],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            '/refrigerators/{id}/temperature-stats' => [
                'get' => [
                    'tags' => ['Temperature Logs'],
                    'summary' => 'Get temperature statistics for a refrigerator',
                    'parameters' => [
                        ['name' => 'id', 'in' => 'path', 'required' => true, 'schema' => ['type' => 'integer']],
                    ],
                    'responses' => [
                        '200' => [
                            'description' => 'Temperature statistics',
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type' => 'object',
                                        'properties' => [
                                            'avg_temp' => ['type' => 'number'],
                                            'min_temp' => ['type' => 'number'],
                                            'max_temp' => ['type' => 'number'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            '/blood-banks' => [
                'get' => [
                    'tags' => ['Blood Banks'],
                    'summary' => 'List all blood banks',
                    'parameters' => [
                        [
                            'name' => 'page',
                            'in' => 'query',
                            'schema' => ['type' => 'integer'],
                        ],
                    ],
                    'responses' => [
                        '200' => [
                            'description' => 'List of blood banks',
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type' => 'object',
                                        'properties' => [
                                            'data' => [
                                                'type' => 'array',
                                                'items' => ['$ref' => '#/components/schemas/BloodBank'],
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'post' => [
                    'tags' => ['Blood Banks'],
                    'summary' => 'Create a new blood bank',
                    'requestBody' => [
                        'required' => true,
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'required' => ['name', 'location', 'phone', 'email'],
                                    'properties' => [
                                        'name' => ['type' => 'string'],
                                        'location' => ['type' => 'string'],
                                        'phone' => ['type' => 'string'],
                                        'email' => ['type' => 'string', 'format' => 'email'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'responses' => [
                        '201' => [
                            'description' => 'Blood bank created',
                            'content' => [
                                'application/json' => [
                                    'schema' => ['$ref' => '#/components/schemas/BloodBank'],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            '/blood-banks/{id}' => [
                'get' => [
                    'tags' => ['Blood Banks'],
                    'summary' => 'Get a blood bank',
                    'parameters' => [
                        ['name' => 'id', 'in' => 'path', 'required' => true, 'schema' => ['type' => 'integer']],
                    ],
                    'responses' => [
                        '200' => [
                            'description' => 'Blood bank details',
                            'content' => [
                                'application/json' => [
                                    'schema' => ['$ref' => '#/components/schemas/BloodBank'],
                                ],
                            ],
                        ],
                    ],
                ],
                'put' => [
                    'tags' => ['Blood Banks'],
                    'summary' => 'Update a blood bank',
                    'parameters' => [
                        ['name' => 'id', 'in' => 'path', 'required' => true, 'schema' => ['type' => 'integer']],
                    ],
                    'requestBody' => [
                        'required' => true,
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'name' => ['type' => 'string'],
                                        'location' => ['type' => 'string'],
                                        'phone' => ['type' => 'string'],
                                        'email' => ['type' => 'string', 'format' => 'email'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'responses' => [
                        '200' => [
                            'description' => 'Blood bank updated',
                            'content' => [
                                'application/json' => [
                                    'schema' => ['$ref' => '#/components/schemas/BloodBank'],
                                ],
                            ],
                        ],
                    ],
                ],
                'delete' => [
                    'tags' => ['Blood Banks'],
                    'summary' => 'Delete a blood bank',
                    'parameters' => [
                        ['name' => 'id', 'in' => 'path', 'required' => true, 'schema' => ['type' => 'integer']],
                    ],
                    'responses' => [
                        '200' => ['description' => 'Blood bank deleted'],
                    ],
                ],
            ],
            '/alerts' => [
                'get' => [
                    'tags' => ['Alerts'],
                    'summary' => 'List all alerts',
                    'responses' => [
                        '200' => [
                            'description' => 'List of alerts',
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type' => 'array',
                                        'items' => ['$ref' => '#/components/schemas/Alert'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            '/alerts/{id}' => [
                'get' => [
                    'tags' => ['Alerts'],
                    'summary' => 'Get an alert',
                    'parameters' => [
                        ['name' => 'id', 'in' => 'path', 'required' => true, 'schema' => ['type' => 'integer']],
                    ],
                    'responses' => [
                        '200' => [
                            'description' => 'Alert details',
                            'content' => [
                                'application/json' => [
                                    'schema' => ['$ref' => '#/components/schemas/Alert'],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            '/alerts/{id}/resolve' => [
                'post' => [
                    'tags' => ['Alerts'],
                    'summary' => 'Resolve an alert',
                    'parameters' => [
                        ['name' => 'id', 'in' => 'path', 'required' => true, 'schema' => ['type' => 'integer']],
                    ],
                    'responses' => [
                        '200' => [
                            'description' => 'Alert resolved',
                            'content' => [
                                'application/json' => [
                                    'schema' => ['$ref' => '#/components/schemas/Alert'],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            '/users' => [
                'get' => [
                    'tags' => ['Users'],
                    'summary' => 'List all users',
                    'responses' => [
                        '200' => [
                            'description' => 'List of users',
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type' => 'array',
                                        'items' => ['$ref' => '#/components/schemas/User'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'post' => [
                    'tags' => ['Users'],
                    'summary' => 'Create a new user',
                    'requestBody' => [
                        'required' => true,
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'required' => ['name', 'email', 'password', 'role'],
                                    'properties' => [
                                        'name' => ['type' => 'string'],
                                        'email' => ['type' => 'string', 'format' => 'email'],
                                        'password' => ['type' => 'string', 'format' => 'password'],
                                        'role' => ['type' => 'string', 'enum' => ['admin', 'staff', 'viewer']],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'responses' => [
                        '201' => [
                            'description' => 'User created',
                            'content' => [
                                'application/json' => [
                                    'schema' => ['$ref' => '#/components/schemas/User'],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            '/users/{id}/assign-bank' => [
                'post' => [
                    'tags' => ['Users'],
                    'summary' => 'Assign user to a blood bank',
                    'parameters' => [
                        ['name' => 'id', 'in' => 'path', 'required' => true, 'schema' => ['type' => 'integer']],
                    ],
                    'requestBody' => [
                        'required' => true,
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'required' => ['blood_bank_id'],
                                    'properties' => [
                                        'blood_bank_id' => ['type' => 'integer'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'responses' => [
                        '200' => ['description' => 'User assigned to blood bank'],
                    ],
                ],
            ],
            '/users/{id}/remove-bank' => [
                'post' => [
                    'tags' => ['Users'],
                    'summary' => 'Remove user from a blood bank',
                    'parameters' => [
                        ['name' => 'id', 'in' => 'path', 'required' => true, 'schema' => ['type' => 'integer']],
                    ],
                    'requestBody' => [
                        'required' => true,
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    'type' => 'object',
                                    'required' => ['blood_bank_id'],
                                    'properties' => [
                                        'blood_bank_id' => ['type' => 'integer'],
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'responses' => [
                        '200' => ['description' => 'User removed from blood bank'],
                    ],
                ],
            ],
            '/dashboard' => [
                'get' => [
                    'tags' => ['Dashboard'],
                    'summary' => 'Get dashboard statistics',
                    'responses' => [
                        '200' => [
                            'description' => 'Dashboard data',
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        'type' => 'object',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }
}
