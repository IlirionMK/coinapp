export async function loadLocaleMessages(i18n, locale) {
    console.log('[i18n] Requested locale:', locale)
    console.log('[i18n] Available locales before:', i18n.global.availableLocales)

    if (!i18n?.global?.availableLocales.includes(locale)) {
        try {
            const messages = await import(
                /* @vite-ignore */ `../locales/${locale}.json`
                )
            console.log(`[i18n] Loaded messages for "${locale}":`, messages)
            i18n.global.setLocaleMessage(locale, messages.default || messages)
        } catch (e) {
            console.error(`[i18n] Failed to load locale "${locale}":`, e)
            return
        }
    }

    i18n.global.locale.value = locale
    console.log('[i18n] Locale switched to:', i18n.global.locale.value)
}
