/*
 * This is cell phone class;
 */
package Electronics_equipment;

/**
 *
 * @author Radi
 */
public class CellPhone extends ElectronicsEquipment{

    private String deviceName;
    private int deviceWeight;
    private Double cost;
    private Double  powerUsage;
    
    public CellPhone(String menufacturerName, String deviceType, String deviceName, int deviceWeight, Double cost, Double powerUsage) {
        super(menufacturerName, deviceType);
        this.deviceName=deviceName;
        this.deviceWeight=deviceWeight;
        this.cost=cost;
        this.powerUsage=powerUsage;
    }
    
    @Override
    public String display(){
        String deviceDetails=("Device name : "+deviceName
                +"\nMenufacturer name : " +menufacturerName
                +"\nDevice weight :"+deviceWeight+" "+"gm"
                +"\nCost per device :$"+cost
                +"\n Power usage :"+powerUsage +" "+"wat"
                +"\n Device type: "+deviceType);
        return deviceDetails;
    }
}
