
import './acl'
import './page/flutterwave-payment-list'



import deDE from './snippet/de-DE.json';
import enGB from './snippet/en-GB.json';
import enUS from './snippet/en-US.json';

Shopware.Module.register('flutterwave-payment-module', {
  type: 'plugin',
  name: 'module.name',
  title: 'module.title',
  description: 'module.description',
  icon: 'default-object-address',
  color: '#00bcd4',

  snippets: {
    'de-DE': deDE,
    'en-GB': enGB,
    'en-US': enUS,
  },
  routes: {
    index: {
      components: {
        default: 'flutterwave-transactions-list',
      },
      path: 'index',
    },
    
    // detail: {
    //   component: 'flutterwave-payment-detail',
    //   path: 'detail/:id',

    //   meta: {
    //     parentPath: 'flutterwave.module.list'
    //   },
    //   props: {
    //     default(route) {
    //       return {
    //         transactionId: route.params.id,
    //       }
    //     },
    //   },
    // },
    // create: {
    //   component: 'flutterwave-payment-detail',
    //   path: 'create',
    //   meta: {
    //     parentPath: 'flutterwave.module.list'
    //   },
    // },
  },
  navigation: [
    {
      path: 'flutterwave.payment.module.index',
      label: 'module.navigation.label',
      id: 'flutterwave',
      //privilege: 'flutterwave_transactions.viewer',
      parent: 'sw-order',
      color: '#ff3d58',
      icon: 'default-object-address',
      position: 150,
    },
  ],
})
