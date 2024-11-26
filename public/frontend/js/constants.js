const FormOptionType = {
    Select: 'Select',
    CheckBox: 'CheckBox',
    RadioBox: 'RadioBox',
}

const url = {
    domain: window.origin,
    domainCoreTCT: 'https://10.11.10.12:8802/',
    domainCatalog: 'https://10.11.10.12:8801/',
    domainUserServer: 'https://10.11.10.13:5443/',
    domainPayment: 'https://10.11.10.12:8802/',

    returnPayment: window.origin + '/PaymentResult',
    APICatalog: window.origin + '/Catalog/get?url=',
    APICoreTCT: window.origin + '/CoreTCT/get?url=',

    ProfileCatalog: 'ProfileCatalog',
    HeadQuarter: 'HeadQuarter',
    Province: 'Province',
}