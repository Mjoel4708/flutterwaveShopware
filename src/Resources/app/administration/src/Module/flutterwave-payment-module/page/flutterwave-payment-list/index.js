import template from './flutterwave-list.html.twig';
import './flutterwave-list.scss'

const { Component } = Shopware;
const { Criteria } = Shopware.Data;

Component.register('flutterwave-transactions-list', {
    template,

    inject: [
        'acl',
        'repositoryFactory',
        'stateStyleDataProviderService'
    ],

    data() {
        return {
            repository: null,
            transactions: null,
            isLoading: true,
            total: 0,
            currentLanguageId: Shopware.Context.api.languageId,
            isUserCustomersViewer: this.isUserCustomersViewer(),
            isUserOrdersViewer: this.isUserOrdersViewer(),
        };
    },

    metaInfo() {
        return {
            title: this.$createTitle()
        };
    },

    computed: {
        columns() {
            return [
            {
                property: 'createdAt',
                dataIndex: 'createdAt',
                label: this.$t('transactions-list.created_at'),
                sortIsAllowed: true,
                allowResize: true,
            },
            {
                property: 'updatedAt',
                dataIndex: 'updatedAt',
                label: this.$t('transactions-list.updated_at'),
                sortIsAllowed: true,
                allowResize: true,
            },
            {
                property: 'flutterwaveTransactionId',
                dataIndex: 'flutterwaveTransactionId',
                label: this.$t('transactions-list.flutterwave_transaction_id'),
                allowResize: true,
                sortIsAllowed: true,
                primary: true
            },
            {
                property: 'order.orderNumber',
                dataIndex: 'orderNumber',
                label: this.$t('transactions-list.order_number'),
                sortIsAllowed: false,
                allowResize: true,
            },
            {
                property: 'amount',
                dataIndex: 'amount',
                label: this.$t('transactions-list.amount'),
                allowResize: true,
                sortIsAllowed: true,
                primary: true
            },
            {
                property: 'customer.lastName',
                dataIndex: 'customer',
                label: this.$t('transactions-list.customer'),
                allowResize: true,
                sortIsAllowed: false,
                primary: true
            },
            {
                property: 'status',
                dataIndex: 'status',
                label: this.$t('transactions-list.status'),
                allowResize: true,
                sortIsAllowed: false,
                primary: true
            },
            {
                property: 'links',
                dataIndex: 'links',
                label: this.$t('transactions-list.links'),
                allowResize: true,
                sortIsAllowed: false,
                align: 'center'
            }];
        }
    },

    created() {
        this.isLoading = true
        this.repository = this.repositoryFactory.create('flutterwave_payment');
        let criteria = new Criteria();
        criteria.addAssociation('order');
        criteria.addAssociation('customer');
        criteria.addAssociation('stateMachineState');
        criteria.addSorting(
            Criteria.sort('flutterwave_payment.createdAt', 'DESC')
        );

        this.repository
            .search(criteria, Shopware.Context.api)
            .then((result) => {
                this.transactions = result;
                this.total = result.total;
                this.isLoading = false;
            });
        
    },

    methods: {
        getVariantFromPaymentState(technicalName) {
            return this.stateStyleDataProviderService.getStyle(
                'order_transaction.state', technicalName
            ).variant;
        },
        
        changeLanguage(newLanguageId) {
            this.currentLanguageId = newLanguageId;
            
        },
        getData(date) {

            if (date <= 0) {
                return '';
            }

            let regex = /(?<year>\d{4}).(?<month>\d{2}).(?<day>\d{2}).(?<hours>\d{2}).(?<minutes>\d{2})/gm; //NOSONAR
            let dateGroup = regex.exec(date)['groups'];

            return dateGroup['year'] + '-' + dateGroup['month'] + '-' + dateGroup['day'] + ' ' + dateGroup['hours'] + ':' + dateGroup['minutes'];
        },

        isUserCustomersViewer() {
            return this.acl.can('customer.viewer');
        },

        isUserOrdersViewer() {
            return this.acl.can('order.viewer');
        }
    }
});