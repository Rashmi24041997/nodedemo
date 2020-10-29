const Joi = require("joi");

const querySchema = Joi.object({
  Name: Joi.string().required(),
  Address: Joi.string().required(),
  Age: Joi.string().required(),
  Gender: Joi.string().required(),
  Phone: Joi.string().required(),
  File: Joi.string().required()
});

module.exports = querySchema;
