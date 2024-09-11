# Krokedil/WooCommerce
A library package for Krokedils plugins that contains helper methods when working with WooCommerce.

### Requirements

- PHP >=7.4

### Installation

To require this package, you need to require it in your composer.json file and also add the repository to the repositories array.

```json
{
    "require": {
        "krokedil/woocommerce": "^1.0"
    },
    "repositories": [
        {
            "type": "vcs",
            "url": "git@github.com:krokedil/woocommerce.git"
        }
    ]
}
```

### Usage

The library currently contains helper classes to generate formated data from a WooCommerce cart, a WooCommerce order or from a Store API Cart.

To get started, its a good idea to setup a configuration array with the data with the configurations that you want to use when generating the data. The configuration will setup some things that will be used through the library, like what format to use for the price, and what slug to use as a prefix for all the filters used.

Currently the config array can contain the following keys:
`slug: string` - The slug to use as a prefix for all the filters used in the library. Default: `krokedil_woocommerce`
`price_format: string` - The format to convert any prices to. Either `minor` or `major` units. Default: `minor`. Minor units will convert the price to the smallest unit of the currency, for example cents for USD. Major units will convert the price to the largest unit of the currency, for example dollars for USD. 100 USD will be turned into 10000 for minor units, and kept at 100 for major units.

Set this config somewhere that is accessible to all the classes that you will be using. For example in a plugin, you could set it in the main plugin file, and then pass it to the classes that you will be using.

After this is done, you can start using the classes. The classes are namespaced, so you can either import the classes you want to use, or you can use the full namespace when calling the classes by using the `\` prefix.

```php
use Krokedil\WooCommerce\Cart;

// Generate the formated data by passing the cart object and the config array to the Cart class.
$cart_data = new Cart( WC()->cart, $config );
// or if you are not using namespaces.
$cart_data = new \Krokedil\WooCommerce\Cart( WC()->cart, $config );

// Then you can access the data by calling the methods on the class.
$array = {
    'order_total'     => $cart_data->get_order_total(),
    'order_total_tax' => $cart_data->get_order_total_tax(),
}

// This will also contain data for all the items in the cart. So if you need to pass data for each cart item, you can do so by looping through the items.
foreach ( $cart_data->get_items() as $item ) {
    $array['items'][] = [
        'name'      => $item->get_name(),
        'price'     => $item->get_price(),
        'quantity'  => $item->get_quantity(),
        'total'     => $item->get_total(),
        'total_tax' => $item->get_total_tax(),
    ];
}
```

The naming of the methods are the same between both the cart helper and the order helper. The only difference is that the order helper will use the order object instead of the cart object. This will allow you to use the same code for both the cart and the order, with just having some logic that decides what helper to use.

```php
use Krokedil\WooCommerce\OrderData;
use Krokedil\WooCommerce\Order;
use Krokedil\WooCommerce\Cart;

/**
 * Get the correct helper class based on if we have a order or not.
 *
 * @param WC_Order $order The WooCommerce order.
 *
 * @return OrderData
 */
function get_helper( $order = null ) {
    $config = ... // Get the config array.

    if ( $order ) {
        return new Order( $order, $config );
    } else {
        return new Cart( WC()->cart, $config );
    }
}

/**
 * Method that will use the helper class.
 *
 * @param WC_Order $order The WooCommerce order.
 * @return array
 */
function my_method( $order = null ) {
    $helper = get_helper( $order );

    $array = [
        'order_total'     => $helper->get_order_total(),
        'order_total_tax' => $helper->get_order_total_tax(),
    ];

    foreach ( $helper->get_items() as $item ) {
        $array['items'][] = [
            'name'      => $item->get_name(),
            'price'     => $item->get_price(),
            'quantity'  => $item->get_quantity(),
            'total'     => $item->get_total(),
            'total_tax' => $item->get_total_tax(),
        ];
    }

    return $array;
}
```

### Filters

All of the data that is set to our formated data will be filtered by the library. This will let you, or customers that use our plugins, modify the data based on their own needs.
The filters are named following a pattern. The pattern is `{$slug}_{$prefix}_{$prop}` where `$slug` is the slug that you set in the config array, `$prefix` is the prefix the helper class adds to the filter. This lets you have different filters for if we get lets say a total from a order or cart. And `$prop` is the property that you want to filter. For example, if you want to filter the order total, you would use the filter `krokedil_woocommerce_cart_total`. Where `krokedil_woocommerce` is the default slug that will be overriden by the config array.
