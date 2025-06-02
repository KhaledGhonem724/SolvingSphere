declare module '@inertiajs/react' {
    /* eslint-disable */
    export const Head: any;
    export function usePage<T = any>(): { props: T };
    export const Link: any;
    export function useForm<T extends Record<string, any>>(
        initialData?: Partial<T>,
    ): {
        data: T;
        setData: (key: keyof T, value: any) => void;
        errors: Partial<Record<keyof T, string>>;
        processing: boolean;
        recentlySuccessful: boolean;
        patch: (route: string, options?: any) => void;
        put: (route: string, options?: any) => void;
        post: (route: string, options?: any) => void;
        reset: (...keys: (keyof T)[]) => void;
    };
}
