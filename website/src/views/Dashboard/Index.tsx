
// MUI Imports
import Grid from '@mui/material/Grid'

// Component Imports
import WelcomeCard from '@/views/Dashboard/WelcomeCard'
import InterestedTopics from '@/views/Dashboard/InterestedTopics'
import PopularInstructors from '@/views/Dashboard/PopularInstructors'
import TopCourses from '@/views/Dashboard/TopCourses'
import UpcomingWebinar from '@/views/Dashboard/UpcomingWebinar'
import AssignmentProgress from '@/views/Dashboard/AssignmentProgress'
import CourseTable from '@/views/Dashboard/CourseTable'

// Data Imports
import { db as getAcademyData } from '@/views/Teacher/db'


import frontCommonStyles from '@views/Styles/styles.module.css'
import styles from '@views/Leader/styles.module.css'

const AcademyDashboard = () => {
  // Vars
  const data = getAcademyData

  return (
    <section id='home' className='relative overflow-hidden pbs-[70px] -mbs-[70px] bg-backgroundPaper z-[1]'>
      <img src={'/images/front-pages/landing-page/hero-bg-light.png'} alt='hero-bg' className={styles.heroSectionBg} />
      <section id='home' className='relative overflow-hidden pbs-[70px] -mbs-[70px] z-[1]'>
        <div className={frontCommonStyles.layoutSpacing} style={{paddingTop: '1.5rem', paddingBottom: '1.5rem'}}>
          <Grid container spacing={6}>
            <Grid item xs={12}>
              <WelcomeCard />
            </Grid>
            <Grid item xs={12} md={8}>
              <InterestedTopics />
            </Grid>
            <Grid item xs={12} sm={6} md={4}>
              <PopularInstructors />
            </Grid>
            <Grid item xs={12} sm={6} md={4}>
              <TopCourses />
            </Grid>
            <Grid item xs={12} sm={6} md={4}>
              <UpcomingWebinar />
            </Grid>
            <Grid item xs={12} sm={6} md={4}>
              <AssignmentProgress />
            </Grid>
            <Grid item xs={12}>
              <CourseTable courseData={data?.courses} />
            </Grid>
          </Grid>
        </div>
      </section>
    </section>
  )
}

export default AcademyDashboard
