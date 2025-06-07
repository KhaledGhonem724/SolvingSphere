import { ImgHTMLAttributes } from 'react';

export default function SLogoIcon(props: ImgHTMLAttributes<HTMLImageElement>) {
    return <img src="/charS.png" alt="Solving Sphere S Logo" {...props} className={`max-h-28 w-auto object-contain ${props.className || ''}`} />;
}
