import { Toaster } from '@/components/ui/toast'; // ✅ Import toast
import AuthLayoutTemplate from '@/layouts/auth/auth-split-layout';
export default function AuthLayout({ children, title, description, ...props }: { children: React.ReactNode; title: string; description: string }) {
    return (
        <>
            <Toaster /> {/* ✅ Tambahkan toaster di sini */}
            <AuthLayoutTemplate title={title} description={description} {...props}>
                {children}
            </AuthLayoutTemplate>
        </>
    );
}
