declare module '@yaireo/tagify' {
    interface TagifySettings {
        whitelist?: string[];
        maxTags?: number;
        dropdown?: {
            maxItems?: number;
            classname?: string;
            enabled?: number;
            closeOnSelect?: boolean;
        };
        placeholder?: string;
    }

    interface TagifyEventDetail {
        value: string;
    }

    class Tagify {
        constructor(element: HTMLElement, settings?: TagifySettings);
        on(eventName: string, callback: (e: CustomEvent<TagifyEventDetail>) => void): void;
        destroy(): void;
    }

    export default Tagify;
}
