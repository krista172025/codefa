const SnowfallEffect = () => {
  return (
    <>
      {[...Array(10)].map((_, i) => (
        <div key={i} className="snowflake">
          ❄
        </div>
      ))}
    </>
  );
};

export default SnowfallEffect;
