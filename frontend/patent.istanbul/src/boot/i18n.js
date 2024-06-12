import { createI18n } from 'vue-i18n';
import messages from 'src/i18n';

export default ({ app }) => {
  const i18n = createI18n({
    legacy: false,
    locale: 'en-US',
    warnHtmlMessage: false,
    globalInjection: true,
    messages,
  });

  app.use(i18n);

  return {
    setup() {
      const i18nLocale = app.config.globalProperties.$i18n.locale;

      // Rest of your code...

      function GetData() {
        // Access the i18nLocale variable here...
        console.log(i18nLocale);

        // Rest of your code...
      }

      // Rest of your code...
    },
  };
};
