# Magento2: Hide Empty Categories

![](details.png)

## Installation

```
composer require hgati/module-hide-empty-categories:dev-master
bin/magento setup:upgrade
```

## Enable / Deisable by Magento CLI Commnad
```
cd /var/www/magento
# Enable
bin/magento config:set hgati_hide_empty_categories/general/enable 1
# Disable (defalt value is disabled)
bin/magento config:set hgati_hide_empty_categories/general/enable 0
```
