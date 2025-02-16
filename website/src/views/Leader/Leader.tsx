'use client'

// Styles Imports
import styles from '@views/Leader/styles.module.css'

import OurTeam from './OurTeam'

const Leader = () => {

    return (
        <section id='home' className='relative overflow-hidden pbs-[70px] -mbs-[70px] bg-backgroundPaper z-[1]'>
            <img src={'/images/front-pages/landing-page/hero-bg-light.png'} alt='hero-bg' className={styles.heroSectionBg} />
            <OurTeam />
        </section>
    )
}

export default Leader
