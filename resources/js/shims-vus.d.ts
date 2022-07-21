declare module '*.vue' {
  import type { defineComponent } from 'vue';
  const component: defineComponent<{}, {}, any>;
  export default component;
}

// need this to fix ts error in app.ts import.meta.glob('./Pages/**/*.vue')
interface ImportMeta {
  glob: (a: string) => any,
}
