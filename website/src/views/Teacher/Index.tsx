'use client'

// React Imports
import { useState } from 'react'

// MUI Imports
import Grid from '@mui/material/Grid'

// Component Imports
import MyCourseHeader from './MyCourseHeader'
import Courses from './Courses'
import ColoredCards from './ColoredCards'
import FreeCourses from './FreeCourses'

import frontCommonStyles from '@views/home/styles.module.css'

import styles from '@views/Leader/styles.module.css'

// Data Imports
import { db as courseData } from '@/views/Teacher/db'


/**
 * ! If you need data using an API call, uncomment the below API code, update the `process.env.API_URL` variable in the
 * ! `.env` file found at root of your project and also update the API endpoints like `/apps/academy` in below example.
 * ! Also, remove the above server action import and the action itself from the `src/app/server/actions.ts` file to clean up unused code
 * ! because we've used the server action for getting our static data.
 */

/* const getAcademyData = async () => {
  // Vars
  const res = await fetch(`${process.env.API_URL}/apps/academy`)

  if (!res.ok) {
    throw new Error('Failed to fetch academy data')
  }

  return res.json()
} */

const AcademyMyCourse = () => {
  // States
  const [searchValue, setSearchValue] = useState('')

  return (
    <section id='home' className='relative overflow-hidden pbs-[70px] -mbs-[70px] bg-backgroundPaper z-[1]'>
      <img src={'/images/front-pages/landing-page/hero-bg-light.png'} alt='hero-bg' className={styles.heroSectionBg} />
      <section id='home' className='relative overflow-hidden pbs-[70px] -mbs-[70px] z-[1]'>
        <div className={frontCommonStyles.layoutSpacing} style={{paddingTop: '1.5rem', paddingBottom: '1.5rem'}}>
          <Grid container spacing={6}>
            <Grid item xs={12}>
              <MyCourseHeader mode={'light'} searchValue={searchValue} setSearchValue={setSearchValue} />
            </Grid>
            <Grid item xs={12}>
              <Courses courseData={courseData.courses} searchValue={searchValue} />
            </Grid>
            <Grid item xs={12}>
              <ColoredCards />
            </Grid>
            <Grid item xs={12}>
              <FreeCourses />
            </Grid>
          </Grid>
        </div>
      </section>
    </section>
  )
}

export default AcademyMyCourse