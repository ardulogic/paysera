import * as yup from 'yup'

export const workingHoursSchema = yup.object().shape({
    hours: yup.array().of(
        yup.object().shape({
            day_of_week: yup.number().min(0).max(6).required(),
            start_time: yup
                .string()
                .when('closed', {
                    is: false,
                    then: schema =>
                        schema.required('Start time is required').matches(/^\d{2}:\d{2}$/, 'Invalid time'),
                    otherwise: schema => schema.nullable(),
                }),
            end_time: yup
                .string()
                .when('closed', {
                    is: false,
                    then: schema =>
                        schema.required('End time is required').matches(/^\d{2}:\d{2}$/, 'Invalid time'),
                    otherwise: schema => schema.nullable(),
                }),
            closed: yup.boolean().required(),
        })
    )
})
