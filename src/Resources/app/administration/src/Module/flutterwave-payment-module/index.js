
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
  version: '1.0.0',

  snippets: {
    'de-DE': deDE,
    'en-GB': enGB,
    'nl-NL': nlNL
  },
  routes: {
    list: {
      component: 'flutterwave-transactions-list',
      path: 'list',

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
      path: 'flutterwave.module.list',
      label: 'Flutterwave Transactions',
      id: 'flutterwave',
      parent: 'sw-order',
      color: '#ff3d58',
      icon: 'default-object-address',
      position: 150,
    },
  ],
})
