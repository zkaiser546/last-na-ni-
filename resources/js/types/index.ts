import { LucideIcon } from 'lucide-vue-next';

export interface NavItem {
    title: string;
    href?: string;
    icon?: LucideIcon;
    children?: NavItem[];
}

export interface BreadcrumbItem {
    title: string;
    href?: string;
}

export interface BreadcrumbItemType {
    title: string;
    href?: string;
}
