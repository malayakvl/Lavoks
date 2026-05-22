import React from 'react'
import { EmblaOptionsType } from 'embla-carousel'
import useEmblaCarousel from 'embla-carousel-react'
import Fade from 'embla-carousel-fade'
import Autoplay from 'embla-carousel-autoplay'
import {
    NextButton,
    PrevButton,
    usePrevNextButtons
} from './EmblaCarouselArrowButtons'
import { DotButton, useDotButton } from './EmblaCarouselDotButton'
import { Link } from '@inertiajs/react'

interface Teaser {
    id: number;
    images: string | null;
    caption: string | null;
    youtube_code: string | null;
    page_url: string | null;
    category_id: number | null;
}

type PropType = {
    teasers: Teaser[]
    options?: EmblaOptionsType
}

const EmblaCarousel = (props: PropType) => {
    const { teasers, options } = props

    // Фильтруем только тизеры с изображениями
    const teasersWithImages = teasers.filter(teaser => teaser.images !== null)

    const [emblaRef, emblaApi] = useEmblaCarousel(options, [
        Fade(),
        Autoplay({ delay: 4000, stopOnInteraction: false })
    ])

    const { selectedIndex, scrollSnaps, onDotButtonClick } =
        useDotButton(emblaApi)

    const {
        prevBtnDisabled,
        nextBtnDisabled,
        onPrevButtonClick,
        onNextButtonClick
    } = usePrevNextButtons(emblaApi)

    // Если нет тизеров с изображениями, показываем заглушку
    if (teasersWithImages.length === 0) {
        return (
            <div className="embla">
                <div className="flex items-center justify-center h-[390px] bg-gray-800 text-white">
                    <p>Немає активних тізерів з зображеннями</p>
                </div>
            </div>
        )
    }

    return (
        <div className="embla">
            <div className="embla__viewport" ref={emblaRef}>
                <div className="embla__container">
                    {teasersWithImages.map((teaser) => (
                        <div className="embla__slide" key={teaser.id}>
                            <img
                                className="embla__slide__img"
                                src={`/storage/${teaser.images}`}
                                alt={teaser.caption || 'Teaser slide'}
                            />
                            <div className="embla__slide__overlay">
                                <div className="embla__slide__content">
                                    {teaser.caption && (
                                        <h2 className="embla__slide__title">{teaser.caption}</h2>
                                    )}
                                    <span className="embla__slide__text">
                                        <p>Шкіряний гаманець для чоловіків і жінок - це не тільки практична та довговічна річ, але й модний аксесуар. </p>
                                        <p>Зручний і стильний гаманець ручної роботи з натуральної шкіри буде предметом захоплення оточуючих і елементом стилю його власника.</p>
                                        </span>
                                    {teaser.category_id && (
                                        <Link
                                            href={`/categories/${teaser.category_id}`}
                                            className="embla__slide__button"
                                        >
                                            Перейти
                                        </Link>
                                    )}
                                </div>
                            </div>
                        </div>
                    ))}
                </div>
            </div>

            <div className="embla__controls">
                <div className="embla__buttons">
                    <PrevButton onClick={onPrevButtonClick} disabled={prevBtnDisabled} />
                    <NextButton onClick={onNextButtonClick} disabled={nextBtnDisabled} />
                </div>

                <div className="embla__dots">
                    {scrollSnaps.map((_, index) => (
                        <DotButton
                            key={index}
                            onClick={() => onDotButtonClick(index)}
                            className={'embla__dot'.concat(
                                index === selectedIndex ? ' embla__dot--selected' : ''
                            )}
                        />
                    ))}
                </div>
            </div>
        </div>
    )
}

export default EmblaCarousel
