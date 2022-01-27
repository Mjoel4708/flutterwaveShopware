Shopware.Service('privileges').addPrivilegeMappingEntry({
    category: 'permissions',
    parent: 'flutterwave',
    key: 'flutterwave_transactions',
    roles: {
        viewer: {
            privileges: [
                'order:read',
                'customer:read',
                'state_machine_state:read',
                'flutterwave_transactions:read',
            ],
            dependencies: []
        },
    }
});

Shopware.Service('privileges').addPrivilegeMappingEntry({
    category: 'permissions',
    parent: 'orders',
    key: 'order',
    roles: {
        viewer: {
            privileges: [
                'flutterwave_transactions:read'
            ],
            dependencies: []
        },
    }
});