// resources/js/utils/loadLocaleMessages.js
export async function loadLocaleMessages(i18n, locale) {
    console.log('DEBUG i18n in loadLocaleMessages:', i18n)
    console.log('DEBUG i18n.global:', i18n.global)

    if (!i18n?.global?.availableLocales.includes(locale)) {
        const messages = await import(`@/locales/${locale}.json`)
        i18n.global.setLocaleMessage(locale, messages.default)
    }

    i18n.global.locale.value = locale
}
