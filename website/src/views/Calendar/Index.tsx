
// MUI Imports
import Card from '@mui/material/Card'

// Component Imports
import CalendarWrapper from '@views/Calendar/CalendarWrapper'

// Styled Component Imports
import AppFullCalendar from '@/libs/styles/AppFullCalendar'

import frontCommonStyles from '@views/home/styles.module.css'

const CalendarApp = () => {
  return (
    <section id='home' className='relative overflow-hidden pbs-[70px] -mbs-[70px] bg-backgroundPaper z-[1]'>
      <div className={frontCommonStyles.layoutSpacing} style={{paddingTop: '1.5rem', paddingBottom: '1.5rem'}}>
        <Card className='overflow-visible'>
          <AppFullCalendar className='app-calendar'>
            <CalendarWrapper />
          </AppFullCalendar>
        </Card>
      </div>
    </section>
  )
}

export default CalendarApp
