
//MUI Imports
import Grid from '@mui/material/Grid'

//Component Imports
import LogisticsStatisticsCard from '@/views/Logistics/dashboard/LogisticsStatisticsCard'
import LogisticsVehicleOverview from '@/views/Logistics/dashboard/LogisticsVehicleOverview'
import LogisticsShipmentStatistics from '@/views/Logistics/dashboard/LogisticsShipmentStatistics'
import LogisticsDeliveryPerformance from '@/views/Logistics/dashboard/LogisticsDeliveryPerformance'
import LogisticsDeliveryExceptions from '@/views/Logistics/dashboard/LogisticsDeliveryExceptions'
import LogisticsOrdersByCountries from '@/views/Logistics/dashboard/LogisticsOrdersByCountries'
import LogisticsOverviewTable from '@/views/Logistics/dashboard/LogisticsOverviewTable'

//Data Imports
import { db as getLogisticsData } from '@/views/Logistics/getLogisticsData'
import { db as getStatisticsData } from '@/views/Logistics/getStatisticsData'

import frontCommonStyles from '@views/Styles/styles.module.css'
import styles from '@views/Leader/styles.module.css'

/**
 * ! If you need data using an API call, uncomment the below API code, update the `process.env.API_URL` variable in the
 * ! `.env` file found at root of your project and also update the API endpoints like `/pages/widget-examples` in below example.
 * ! Also, remove the above server action import and the action itself from the `src/app/server/actions.ts` file to clean up unused code
 * ! because we've used the server action for getting our static data.
 */

/* const getStatisticsData = async () => {
  // Vars
  const res = await fetch(`${process.env.API_URL}/pages/widget-examples`)

  if (!res.ok) {
    throw new Error('Failed to fetch statisticsData')
  }

  return res.json()
} */

/**
 * ! If you need data using an API call, uncomment the below API code, update the `process.env.API_URL` variable in the
 * ! `.env` file found at root of your project and also update the API endpoints like `/apps/logistics` in below example.
 * ! Also, remove the above server action import and the action itself from the `src/app/server/actions.ts` file to clean up unused code
 * ! because we've used the server action for getting our static data.
 */

/* const getLogisticsData = async () => {
  // Vars
  const res = await fetch(`${process.env.API_URL}/apps/logistics`)

  if (!res.ok) {
    throw new Error('Failed to fetch logistics data')
  }

  return res.json()
} */

const LogisticsDashboard = () => {
  // Vars
  const data = getStatisticsData
  const vehicleData = getLogisticsData
  
  return (
    <section id='home' className='relative overflow-hidden pbs-[70px] -mbs-[70px] bg-backgroundPaper z-[1]'>
      <img src={'/images/front-pages/landing-page/hero-bg-light.png'} alt='hero-bg' className={styles.heroSectionBg} />
      <section id='home' className='relative overflow-hidden pbs-[70px] -mbs-[70px] z-[1]'>
        <div className={frontCommonStyles.layoutSpacing} style={{paddingTop: '1.5rem', paddingBottom: '1.5rem'}}>
          <Grid container spacing={6}>
            <Grid item xs={12}>
              <LogisticsStatisticsCard data={data?.statsHorizontalWithBorder} />
            </Grid>
            <Grid item xs={12} md={6}>
              <LogisticsVehicleOverview />
            </Grid>
            <Grid item xs={12} md={6}>
              <LogisticsShipmentStatistics />
            </Grid>
            <Grid item xs={12} md={4}>
              <LogisticsDeliveryPerformance />
            </Grid>
            <Grid item xs={12} md={4}>
              <LogisticsDeliveryExceptions />
            </Grid>
            <Grid item xs={12} md={4}>
              <LogisticsOrdersByCountries />
            </Grid>
            <Grid item xs={12}>
              <LogisticsOverviewTable vehicleData={vehicleData?.vehicles} />
            </Grid>
          </Grid>
        </div>
      </section>
    </section>
  )
}

export default LogisticsDashboard
