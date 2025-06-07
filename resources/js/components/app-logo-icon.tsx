import { ImgHTMLAttributes } from 'react';

export default function AppLogoIcon(props: ImgHTMLAttributes<HTMLImageElement>) {
    return <img src="/logoS.jpg" alt="Solveing Sphere Logo" {...props} className={`max-h-28 w-auto object-contain ${props.className || ''}`} />;
}
