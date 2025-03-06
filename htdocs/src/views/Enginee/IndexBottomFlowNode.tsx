// ** React Imports
import { Fragment } from 'react'

// ** MUI Imports
import Card from '@mui/material/Card'
import Step from '@mui/material/Step'
import Stepper from '@mui/material/Stepper'
import StepLabel from '@mui/material/StepLabel'
import Typography from '@mui/material/Typography'

// ** Custom Components Imports
import IndexBottomFlowNodeDot from './IndexBottomFlowNodeDot'

// ** Styled Components
import StepperWrapper from 'src/@core/styles/mui/stepper'

interface IndexBottomFlowNodeType {
    ApprovalNodeFields: string[]
    ApprovalNodeCurrentField: string
    ActiveStep: number
    ApprovalNodeTitle: string
    Memo:string
    DebugSql:string[]
    AdminFilterTipText: string
  }
  
const IndexBottomFlowNode = (props: IndexBottomFlowNodeType) => {
    const { ApprovalNodeFields, ActiveStep, ApprovalNodeTitle, DebugSql, Memo, AdminFilterTipText } = props
    
    return (
        <Fragment>
            <Card> 
                { ApprovalNodeFields && ApprovalNodeTitle ? 
                    <Fragment>
                        <Typography sx={{pl: 2, ml: 3, my: 2}}>{ApprovalNodeTitle}</Typography>
                        <StepperWrapper sx={{ml: 3, mb: 2}}>
                        { ActiveStep != undefined ?
                        <Stepper activeStep={ActiveStep}>
                            {ApprovalNodeFields.map((node, index) => {
                            const labelProps: {
                                error?: boolean
                            } = {}
                            
                            return (
                                <Step key={index}>
                                    <StepLabel {...labelProps} StepIconComponent={IndexBottomFlowNodeDot}>
                                        <div className='step-label'>
                                            <Typography className='step-number'>{`0${index + 1}`}</Typography>
                                            <Typography className='step-title' sx={{ whiteSpace: 'nowrap', overflow: 'hidden', textOverflow: 'ellipsis' }}>{node}</Typography>
                                        </div>
                                    </StepLabel>
                                </Step>
                            )
                            })}
                        </Stepper>
                        :
                        null
                        }
                        </StepperWrapper>
                    </Fragment>
                    : '' 
                }
                
                <Typography sx={{ pl: 2, ml: 3, mb: 2, whiteSpace: 'pre-wrap', overflowX: 'auto' }}>
                {DebugSql.map((sql, index) => (
                    <div key={index}>{sql}</div>
                ))}
                </Typography>

                { Memo && <Typography sx={{pl: 2, ml: 3, mb: 2}}>{Memo}</Typography> }
                
                { AdminFilterTipText && <Typography sx={{pl: 2, ml: 3, mb: 2, fontSize: '0.875rem', color: 'text.secondary'}}>{AdminFilterTipText}</Typography> }

            </Card>
            
        </Fragment>
    )
}

export default IndexBottomFlowNode
