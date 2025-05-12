export async function loadLocaleMessages(i18n, locale) {

    if (!i18n?.global?.availableLocales.includes(locale)) {
        try {
            const messages = await import(
                /* @vite-ignore */ `../locales/${locale}.json`
                )
            i18n.global.setLocaleMessage(locale, messages.default || messages)
        } catch (e) {
            return
        }
    }

    i18n.global.locale.value = locale
}
