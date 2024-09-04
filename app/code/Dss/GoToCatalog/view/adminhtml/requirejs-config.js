var config = {
    deps: [
        "Dss_GoToCatalog/catalog/product-mixin"
    ],
    config: {
        mixins: {
            "Magento_Catalog/catalog/category/form": {
                "Dss_GoToCatalog/catalog/category/form-mixin": true,
            },
        },
    },
};
