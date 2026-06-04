import * as React from "react";
import { NavigationMenu } from "radix-ui";
import classNames from "classnames";
import { HamburgerMenuIcon, Cross1Icon } from "@radix-ui/react-icons";
import { DigitalClock } from "./DigitalClock";
import { SearchInput } from "./SearchInput";
import { CartButton } from "./CartButton";
import { CartDropdown } from "./CartDropdown";

type Category = {
    id: number;
    image?: string;
    current_translation?: {
        title: string;
    };
    children?: Category[];
};

type Props = {
    categories: Category[];
};

const ListItem = React.forwardRef(
    ({ className, children, title, image, ...props }, forwardedRef) => {
        // Replace 'original' path with 'cut' path and change extension to .png
        const cutImage = image
            ? image.replace('categories/original/', 'categories/cut/').replace('.webp', '.webp')
            : null;

        return (
            <li>
                <NavigationMenu.Link asChild>
                    <a
                        className={classNames("ListItemLink", className)}
                        {...props}
                        ref={forwardedRef}
                    >
                        <div className="image-menu-link flex gap-4">
                            <div className="image-div-cat">
                                {cutImage && (
                                    <img
                                        src={`/storage/${cutImage}`}
                                        alt={title}
                                        className="ListItemImage"
                                    />
                                )}
                            </div>
                            <div className="text-content relative">
                                <span className="ListItemTextNew cat-title">{children}</span>
                                {/*<span className="cat-description">Тонкі компактні картхолдери на любий смак</span>*/}
                                {/* Ссылку "Переглянути →" можно зашить прямо сюда в структуру, как на скетче */}
                                <span className="view-link-arrow">Переглянути &rarr;</span>
                            </div>
                        </div>
                    </a>
                </NavigationMenu.Link>
            </li>
        );
    },
);

export default function Footer({ categories }: Props) {
    const [mobileMenuOpen, setMobileMenuOpen] = React.useState(false);
    const [cartOpen, setCartOpen] = React.useState(false);
    const [cartCount, setCartCount] = React.useState(0);

    React.useEffect(() => {
        const handler = () => setCartOpen(false);
        document.addEventListener("click", handler);
        return () => document.removeEventListener("click", handler);
    }, []);

    return (
        <footer>
            <div className="w-full top-line">

            </div>

            <div className="w-full max-w-[1100px] mx-auto px-6 py-1 flex items-center justify-between gap-12">


            </div>

            {/* Desktop Navigation */}
            <div className="w-full mx-auto px-0 py-[0px] hidden md:block ">
                <NavigationMenu.Root className="NavigationMenuRoot">
                    <NavigationMenu.List className="NavigationMenuList sub-menu">
                        {/*<a href="#" className="text-[#e5c158] hover:text-white transition-colors drop-shadow-[0_1px_2px_rgba(0,0,0,0.8)] flex items-center gap-1">*/}
                        {/*    🔥 Знижки*/}
                        {/*</a>*/}
                        {categories.map((cat) => (
                            <NavigationMenu.Item key={cat.id}>
                                {cat.children && cat.children.length > 0 ? (
                                    <>
                                        <NavigationMenu.Trigger className="NavigationMenuTrigger">
                                            <span className="parent-menu">{cat.current_translation?.title}</span>
                                        </NavigationMenu.Trigger>


                                    </>
                                ) : (
                                    <NavigationMenu.Link
                                        className="NavigationMenuLink"
                                        href={`/category/${cat.id}`}
                                    >
                                        {cat.current_translation?.title}
                                    </NavigationMenu.Link>
                                )}
                            </NavigationMenu.Item>
                        ))}

                        <NavigationMenu.Indicator className="NavigationMenuIndicator">
                            <div className="Arrow" />
                        </NavigationMenu.Indicator>
                    </NavigationMenu.List>

                    <div className="ViewportPosition">
                        <NavigationMenu.Viewport className="NavigationMenuViewport" />
                    </div>
                </NavigationMenu.Root>
            </div>


        </footer>
    );
}
