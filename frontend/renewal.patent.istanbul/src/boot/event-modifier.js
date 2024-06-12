import { boot } from 'quasar/wrappers';

export default boot(({ app }) => {
    app.directive('passive', {
        mounted(el, binding) {
            el.addEventListener(binding.arg, binding.value, { passive: true });
        }
    });
});
