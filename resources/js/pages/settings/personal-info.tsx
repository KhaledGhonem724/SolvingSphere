import { type BreadcrumbItem, type SharedData } from '@/types';
import { Transition } from '@headlessui/react';
import { Head, useForm as useInertiaForm, usePage } from '@inertiajs/react';
import { FormEventHandler } from 'react';

import HeadingSmall from '@/components/heading-small';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/app-layout';
import SettingsLayout from '@/layouts/settings/layout';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Personal info settings',
        href: '/settings/personal-info',
    },
];

type PersonalInfoForm = {
    name: string;
    linkedin_url: string;
    github_url: string;
    portfolio_url: string;
};

interface PersonalInfoProps {
    socialLinks: {
        linkedin_url: string | null;
        github_url: string | null;
        portfolio_url: string | null;
    };
}

export default function PersonalInfo({ socialLinks }: PersonalInfoProps) {
    const { auth } = usePage<SharedData>().props;

    const { data, setData, patch, errors, processing, recentlySuccessful } = useInertiaForm<PersonalInfoForm>({
        name: auth.user.name,
        linkedin_url: socialLinks.linkedin_url || '',
        github_url: socialLinks.github_url || '',
        portfolio_url: socialLinks.portfolio_url || '',
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();

        patch(route('settings.personal-info.update'), {
            preserveScroll: true,
        });
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Personal Info Settings" />

            <SettingsLayout>
                <div className="space-y-6">
                    <HeadingSmall title="Personal Information" description="Update your name and social media profiles" />

                    <form onSubmit={submit} className="space-y-6">
                        <div className="grid gap-2">
                            <Label htmlFor="name">Name</Label>
                            <Input
                                id="name"
                                className="mt-1 block w-full"
                                value={data.name}
                                onChange={(e) => setData('name', e.target.value)}
                                required
                                autoComplete="name"
                                placeholder="Full name"
                            />
                            <InputError className="mt-2" message={errors.name} />
                        </div>

                        <div className="grid gap-2">
                            <Label htmlFor="linkedin_url">LinkedIn Profile URL</Label>
                            <Input
                                id="linkedin_url"
                                className="mt-1 block w-full"
                                value={data.linkedin_url}
                                onChange={(e) => setData('linkedin_url', e.target.value)}
                                placeholder="https://linkedin.com/in/your-profile"
                            />
                            <InputError className="mt-2" message={errors.linkedin_url} />
                        </div>

                        <div className="grid gap-2">
                            <Label htmlFor="github_url">GitHub Profile URL</Label>
                            <Input
                                id="github_url"
                                className="mt-1 block w-full"
                                value={data.github_url}
                                onChange={(e) => setData('github_url', e.target.value)}
                                placeholder="https://github.com/your-username"
                            />
                            <InputError className="mt-2" message={errors.github_url} />
                        </div>

                        <div className="grid gap-2">
                            <Label htmlFor="portfolio_url">Portfolio Website URL</Label>
                            <Input
                                id="portfolio_url"
                                className="mt-1 block w-full"
                                value={data.portfolio_url}
                                onChange={(e) => setData('portfolio_url', e.target.value)}
                                placeholder="https://your-portfolio.com"
                            />
                            <InputError className="mt-2" message={errors.portfolio_url} />
                        </div>

                        <div className="flex items-center gap-4">
                            <Button disabled={processing}>Save</Button>

                            <Transition
                                show={recentlySuccessful}
                                enter="transition ease-in-out"
                                enterFrom="opacity-0"
                                leave="transition ease-in-out"
                                leaveTo="opacity-0"
                            >
                                <p className="text-sm text-neutral-600">Saved</p>
                            </Transition>
                        </div>
                    </form>
                </div>
            </SettingsLayout>
        </AppLayout>
    );
}
