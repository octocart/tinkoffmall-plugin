# Tinkoff Mall Plugin

Accept Credit Cards and Debit Cards on your Mall store.

### Requirements

* [Offline.Mall](https://octobercms.com/plugin/offline-mall) plugin

## DOCUMENTATION

# Installation
1. Add **Tinkoff Mall Plugin** plugin to a project. 
2. Create new Tinkoff account [here](https://oplata.tinkoff.ru/landing/business).
3. Enable notifications in terminal settings (https://oplata.tinkoff.ru/lk2/merchants/terminals/edit/test/XXXXX) and set URL (https://www.example.com/tinkoff/notify)
4. Copy **config.example.php** to **config.php** and set your **merchantId** and **secretKey** from your [profile](https://oplata.tinkoff.ru/lk2/merchants).
5. Add new Payment method: **/backend/offline/mall/paymentmethods**.
6. Run the tests (https://oplata.tinkoff.ru/lk2/merchants/terminals/test/XXXX) to complete the integration.
