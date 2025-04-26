// ** MUI Import
import Box from '@mui/material/Box'
import Card from '@mui/material/Card'
import { styled } from '@mui/material/styles'
import TimelineDot from '@mui/lab/TimelineDot'
import TimelineItem from '@mui/lab/TimelineItem'
import Typography from '@mui/material/Typography'
import CardContent from '@mui/material/CardContent'
import TimelineContent from '@mui/lab/TimelineContent'
import TimelineSeparator from '@mui/lab/TimelineSeparator'
import TimelineConnector from '@mui/lab/TimelineConnector'
import MuiTimeline, { TimelineProps } from '@mui/lab/Timeline'
import { OverridableStringUnion } from '@mui/types';

// Styled Timeline component
const Timeline = styled(MuiTimeline)<TimelineProps>({
  paddingLeft: 0,
  paddingRight: 0,
  '& .MuiTimelineItem-root': {
    width: '100%',
    '&:before': {
      display: 'none'
    }
  }
})

const ApprovalNodesList = ({ approvalNodes }: any) => {

  //console.log("approvalNodes", approvalNodes)

  const NodesLength = approvalNodes.length

  const colorList = ['error','primary','info','warning','success','error','primary','info','warning','success','error','primary','info','warning','success']

  return (
    <Card>
      <Typography variant='body2' sx={{ ml: 2, color: 'text.primary', fontWeight: 600 }}>工作进度</Typography>
      <CardContent sx={{ p: 2}}>
        <Timeline sx={{ my: 0, py: 0 }}>
          {approvalNodes && approvalNodes.map((item: any, index: number)=>{            
            
            return (
              <TimelineItem key={index}>
                <TimelineSeparator>
                  <TimelineDot color={colorList[index] as OverridableStringUnion< 'inherit' | 'grey' | 'primary' | 'secondary' | 'error' | 'info' | 'success' | 'warning', {} >} />
                  {index + 1 < NodesLength && <TimelineConnector /> }                  
                </TimelineSeparator>
                <TimelineContent sx={{ pr: 0, mt: 0, mb: theme => `${theme.spacing(1.5)} !important` }}>
                  <Box
                    sx={{
                      mb: 2.5,
                      display: 'flex',
                      flexWrap: 'wrap',
                      alignItems: 'center',
                      justifyContent: 'space-between'
                    }}
                  >
                    <Typography variant='body2' sx={{ mr: 2, color: 'text.primary' }}>
                      {item.经办步骤} - {item.步骤状态} - {item.USER_NAME}
                    </Typography>
                    <Typography variant='caption' sx={{ color: 'text.disabled' }}>
                      {item.工作接收时间}
                    </Typography>
                  </Box>
                  <Typography variant='body2'>主办说明: {item.主办说明}</Typography>
                </TimelineContent>
              </TimelineItem>
            )
          })}
        </Timeline>
      </CardContent>
    </Card>
  )
}

export default ApprovalNodesList
